<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view dokumen')->only(['index', 'show', 'download']);
        $this->middleware('permission:create dokumen')->only(['create', 'store']);
        $this->middleware('permission:edit dokumen')->only(['edit', 'update']);
        $this->middleware('permission:delete dokumen')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Dokumen::with(['karyawan', 'uploader']);

        // Filter based on roles: Karyawan can only see their own documents or company-wide documents
        if ($user->hasRole('Karyawan')) {
            $karyawan = Karyawan::where('user_id', $user->id)->first();
            $karyawanId = $karyawan ? $karyawan->id : 0;
            $query->where(function ($q) use ($karyawanId) {
                $q->where('karyawan_id', $karyawanId)
                  ->orWhereNull('karyawan_id');
            });
        }

        // Apply filters from request
        if ($request->has('search') && $request->search) {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }

        if ($request->has('tipe_dokumen') && $request->tipe_dokumen) {
            $query->where('tipe_dokumen', $request->tipe_dokumen);
        }

        if ($request->has('karyawan_id') && $request->karyawan_id) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        $dokumen = $query->latest()->paginate(15);
        $karyawanList = Karyawan::orderBy('nama_lengkap')->get();

        return view('dokumen.index', compact('dokumen', 'karyawanList'));
    }

    public function create()
    {
        $karyawanList = Karyawan::orderBy('nama_lengkap')->get();
        return view('dokumen.create', compact('karyawanList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_dokumen' => 'required|string|max:255',
            'tipe_dokumen' => 'required|in:kontrak,sk,sertifikat,personal,lainnya',
            'file' => 'required|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,zip,jpg,jpeg,png,gif,svg',
            'karyawan_id' => 'nullable|exists:karyawan,id',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $uploadedFile = $request->file('file');
                $originalName = $uploadedFile->getClientOriginalName();
                $fileExtension = $uploadedFile->getClientOriginalExtension();
                $fileSize = $uploadedFile->getSize();
                
                // Store file under /storage/dokumen
                $filePath = $uploadedFile->store('dokumen', 'public');

                Dokumen::create([
                    'karyawan_id' => $request->karyawan_id,
                    'nama_dokumen' => $request->nama_dokumen,
                    'tipe_dokumen' => $request->tipe_dokumen,
                    'file_path' => $filePath,
                    'file_size' => $fileSize,
                    'file_type' => $fileExtension,
                    'deskripsi' => $request->deskripsi,
                    'uploaded_by' => auth()->id(),
                ]);

                Log::info('Document uploaded successfully', [
                    'name' => $originalName,
                    'size' => $fileSize,
                    'uploaded_by' => auth()->id()
                ]);

                return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diunggah');
            }
        } catch (\Exception $e) {
            Log::error('Document upload failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengunggah dokumen: ' . $e->getMessage())->withInput();
        }

        return back()->with('error', 'Berkas berkas tidak valid.')->withInput();
    }

    public function show(Dokumen $dokumen)
    {
        return view('dokumen.show', compact('dokumen'));
    }

    public function edit(Dokumen $dokumen)
    {
        $karyawanList = Karyawan::orderBy('nama_lengkap')->get();
        return view('dokumen.edit', compact('dokumen', 'karyawanList'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $validator = Validator::make($request->all(), [
            'nama_dokumen' => 'required|string|max:255',
            'tipe_dokumen' => 'required|in:kontrak,sk,sertifikat,personal,lainnya',
            'karyawan_id' => 'nullable|exists:karyawan,id',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $dokumen->update($request->all());
        return redirect()->route('dokumen.index')->with('success', 'Metadata dokumen berhasil diperbarui');
    }

    public function destroy(Dokumen $dokumen)
    {
        try {
            if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            $dokumen->delete();
            return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Document delete failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function download(Dokumen $dokumen)
    {
        $user = auth()->user();

        // Karyawan can only download their own or public documents
        if ($user->hasRole('Karyawan')) {
            $karyawan = Karyawan::where('user_id', $user->id)->first();
            $karyawanId = $karyawan ? $karyawan->id : 0;
            if ($dokumen->karyawan_id !== null && $dokumen->karyawan_id !== $karyawanId) {
                abort(403, 'Anda tidak memiliki hak akses untuk mengunduh berkas ini.');
            }
        }

        $path = storage_path('app/public/' . $dokumen->file_path);

        if (!file_exists($path)) {
            abort(404, 'Berkas dokumen tidak ditemukan di penyimpanan server.');
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => $this->getMimeType($extension),
        ];

        return response()->download($path, $dokumen->nama_dokumen . '.' . $extension, $headers);
    }

    private function getMimeType($extension)
    {
        $mimes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt'  => 'text/plain',
            'csv'  => 'text/csv',
            'zip'  => 'application/zip',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'svg'  => 'image/svg+xml',
        ];

        return $mimes[strtolower($extension)] ?? 'application/octet-stream';
    }
}
