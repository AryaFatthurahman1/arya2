<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Exports\AbsensiExport;
use App\Imports\AbsensiImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view absensi')->only(['index', 'show']);
        $this->middleware('permission:create absensi')->only(['create', 'store', 'import']);
        $this->middleware('permission:edit absensi')->only(['edit', 'update']);
        $this->middleware('permission:delete absensi')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Absensi::with('karyawan');
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->has('tanggal_selesai') && $request->tanggal_selesai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }
        if ($request->has('karyawan_id') && $request->karyawan_id) {
            $query->where('karyawan_id', $request->karyawan_id);
        }
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        $absensi = $query->latest('tanggal')->paginate(15);
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('absensi.index', compact('absensi', 'karyawan'));
    }

    public function create()
    {
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('absensi.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:karyawan,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:500',
            'foto_absen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->except('foto_absen');
        $data['created_by'] = auth()->id();
        if ($request->hasFile('foto_absen')) {
            $data['foto_absen'] = $request->file('foto_absen')->store('absensi', 'public');
        }
        Absensi::create($data);
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dicatat');
    }

    public function show(Absensi $absensi)
    {
        $absensi->load('karyawan');
        return view('absensi.show', compact('absensi'));
    }

    public function edit(Absensi $absensi)
    {
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('absensi.edit', compact('absensi', 'karyawan'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:karyawan,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:500',
            'foto_absen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->except('foto_absen');
        $data['updated_by'] = auth()->id();
        if ($request->hasFile('foto_absen')) {
            if ($absensi->foto_absen) {
                Storage::disk('public')->delete($absensi->foto_absen);
            }
            $data['foto_absen'] = $request->file('foto_absen')->store('absensi', 'public');
        }
        $absensi->update($data);
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diperbarui');
    }

    public function export(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $nama = 'rekap-absensi';
        if ($tahun) $nama .= '-' . $tahun;
        if ($bulan) $nama .= '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $nama .= '.xlsx';
        return Excel::download(new AbsensiExport($tahun, $bulan), $nama);
    }

    public function destroy(Absensi $absensi)
    {
        if ($absensi->foto_absen) {
            Storage::disk('public')->delete($absensi->foto_absen);
        }
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus');
    }

    public function absenOnline(Request $request)
    {
        $karyawan = Karyawan::all();
        return view('absensi.online', compact('karyawan'));
    }

    public function clockIn(Request $request)
    {
        $karyawanId = $request->karyawan_id;
        $today = Carbon::today();
        $existing = Absensi::where('karyawan_id', $karyawanId)->whereDate('tanggal', $today)->first();
        if ($existing && $existing->jam_masuk) {
            return back()->with('error', 'Sudah absen masuk hari ini');
        }
        Absensi::updateOrCreate(
            ['karyawan_id' => $karyawanId, 'tanggal' => $today],
            ['jam_masuk' => Carbon::now()->format('H:i:s'), 'status' => 'hadir', 'created_by' => $karyawanId]
        );
        return back()->with('success', 'Berhasil absen masuk');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            Log::warning('Absensi import validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator);
        }

        try {
            Excel::import(new AbsensiImport, $request->file('file'));
            Log::info('Absensi imported', ['user_id' => auth()->id()]);
            return back()->with('success', 'Data absensi berhasil diimport');
        } catch (\Exception $e) {
            Log::error('Absensi import failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengimport data absensi: ' . $e->getMessage());
        }
    }
}