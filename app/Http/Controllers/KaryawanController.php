<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\SatuanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KaryawanImport;
use App\Exports\KaryawanExport;
use App\Exports\KaryawanTemplateExport;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view karyawan')->only(['index', 'show']);
        $this->middleware('permission:create karyawan')->only(['create', 'store', 'import']);
        $this->middleware('permission:edit karyawan')->only(['edit', 'update']);
        $this->middleware('permission:delete karyawan')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Karyawan::with(['jabatan', 'satuanKerja', 'user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%");
        }

        $karyawan = $query->paginate(10);

        Log::info('Viewing karyawan list', ['user_id' => auth()->id()]);

        return view('karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $jabatan = Jabatan::all();
        $satuanKerja = SatuanKerja::all();
        return view('karyawan.create', compact('jabatan', 'satuanKerja'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:20|unique:karyawan,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'status_pernikahan' => 'required|in:belum_menikah,menikah,cerai',
            'alamat' => 'required|string|max:1000',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:karyawan,email',
            'jabatan_id' => 'required|exists:jabatan,id',
            'satuan_kerja_id' => 'required|exists:satuan_kerja,id',
            'tanggal_masuk' => 'required|date',
            'status_karyawan' => 'required|in:tetap,kontrak,percobaan',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_bank' => 'nullable|string|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            Log::warning('Karyawan create validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except('foto_profil');

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $data['foto_profil'] = $path;
        }

        Karyawan::create($data);

        Log::info('Karyawan created', ['user_id' => auth()->id(), 'nik' => $request->nik]);

        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan berhasil ditambahkan');
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['jabatan', 'satuanKerja', 'absensi', 'izin', 'tugas', 'penilaian', 'penggajian']);
        Log::info('Viewing karyawan detail', ['karyawan_id' => $karyawan->id]);
        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $jabatan = Jabatan::all();
        $satuanKerja = SatuanKerja::all();
        return view('karyawan.edit', compact('karyawan', 'jabatan', 'satuanKerja'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:20|unique:karyawan,nik,' . $karyawan->id,
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'status_pernikahan' => 'required|in:belum_menikah,menikah,cerai',
            'alamat' => 'required|string|max:1000',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:karyawan,email,' . $karyawan->id,
            'jabatan_id' => 'required|exists:jabatan,id',
            'satuan_kerja_id' => 'required|exists:satuan_kerja,id',
            'tanggal_masuk' => 'required|date',
            'status_karyawan' => 'required|in:tetap,kontrak,percobaan',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_bank' => 'nullable|string|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            Log::warning('Karyawan update validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except('foto_profil');

        if ($request->hasFile('foto_profil')) {
            if ($karyawan->foto_profil) {
                Storage::disk('public')->delete($karyawan->foto_profil);
            }
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $data['foto_profil'] = $path;
        }

        $karyawan->update($data);

        Log::info('Karyawan updated', ['user_id' => auth()->id(), 'karyawan_id' => $karyawan->id]);

        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->foto_profil) {
            Storage::disk('public')->delete($karyawan->foto_profil);
        }

        $karyawan->delete();

        Log::info('Karyawan deleted', ['user_id' => auth()->id(), 'karyawan_id' => $karyawan->id]);

        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new KaryawanExport, 'data-karyawan-' . date('Y-m-d') . '.xlsx');
    }

    public function downloadTemplate()
    {
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        $satuanKerja = SatuanKerja::orderBy('nama_satuan_kerja')->get();
        return Excel::download(
            new KaryawanTemplateExport($jabatan, $satuanKerja),
            'template-import-karyawan.xlsx'
        );
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            Log::warning('Karyawan import validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator);
        }

        try {
            Excel::import(new KaryawanImport, $request->file('file'));
            Log::info('Karyawan imported', ['user_id' => auth()->id()]);
            return back()->with('success', 'Data karyawan berhasil diimport');
        } catch (\Exception $e) {
            Log::error('Karyawan import failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
