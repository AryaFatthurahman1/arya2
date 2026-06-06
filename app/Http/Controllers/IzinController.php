<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIzin;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class IzinController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view izin')->only(['index', 'show']);
        $this->middleware('permission:approve izin')->only(['approve', 'reject']);
    }

    public function index(Request $request)
    {
        $query = PengajuanIzin::with(['karyawan', 'verifiedBy']);
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('karyawan_id') && $request->karyawan_id) {
            $query->where('karyawan_id', $request->karyawan_id);
        }
        $izin = $query->latest()->paginate(15);
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('izin.index', compact('izin', 'karyawan'));
    }

    public function create()
    {
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('izin.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:karyawan,id',
            'jenis_izin' => 'required|in:sakit,cuti,izin_khusus',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
            'bukti_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->except('bukti_dokumen');
        if ($request->hasFile('bukti_dokumen')) {
            $data['bukti_dokumen'] = $request->file('bukti_dokumen')->store('izin', 'public');
        }
        PengajuanIzin::create($data);
        return redirect()->route('leaves.index')->with('success', 'Pengajuan izin berhasil dikirim');
    }

    public function show(PengajuanIzin $izin)
    {
        $izin->load(['karyawan', 'verifiedBy']);
        return view('izin.show', compact('izin'));
    }

    public function approve(Request $request, PengajuanIzin $izin)
    {
        $validator = Validator::make($request->all(), [
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $izin->update([
            'status' => 'disetujui',
            'catatan_verifikasi' => $request->catatan_verifikasi ?: 'Disetujui oleh ' . auth()->user()->name,
            'verified_by' => auth()->id(),
            'verified_at' => Carbon::now(),
        ]);
        Log::info('Izin disetujui', ['izin_id' => $izin->id, 'verified_by' => auth()->id()]);
        return redirect()->route('leaves.index')->with('success', 'Izin berhasil disetujui');
    }

    public function reject(Request $request, PengajuanIzin $izin)
    {
        $validator = Validator::make($request->all(), [
            'catatan_verifikasi' => 'required|string|max:500',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $izin->update([
            'status' => 'ditolak',
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'verified_by' => auth()->id(),
            'verified_at' => Carbon::now(),
        ]);
        Log::info('Izin ditolak', ['izin_id' => $izin->id, 'verified_by' => auth()->id()]);
        return redirect()->route('leaves.index')->with('success', 'Izin berhasil ditolak');
    }

    public function destroy(PengajuanIzin $izin)
    {
        if ($izin->bukti_dokumen) {
            Storage::disk('public')->delete($izin->bukti_dokumen);
        }
        $izin->delete();
        return redirect()->route('leaves.index')->with('success', 'Pengajuan izin berhasil dihapus');
    }
}