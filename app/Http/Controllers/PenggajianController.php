<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Exports\PenggajianExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PenggajianController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage penggajian')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Penggajian::with('karyawan');
        if ($request->has('periode') && $request->periode) {
            $query->where('periode', $request->periode);
        }
        if ($request->has('karyawan_id') && $request->karyawan_id) {
            $query->where('karyawan_id', $request->karyawan_id);
        }
        $penggajian = $query->latest('periode')->paginate(15);
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('penggajian.index', compact('penggajian', 'karyawan'));
    }

    public function create()
    {
        $karyawan = Karyawan::with('jabatan')->orderBy('nama_lengkap')->get();
        return view('penggajian.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|date',
            'tunjangan_lainnya' => 'nullable|numeric|min:0',
            'potongan_lainnya' => 'nullable|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            return DB::transaction(function () use ($request) {
                $karyawan = Karyawan::with('jabatan')->findOrFail($request->karyawan_id);
                $periode = Carbon::parse($request->periode);

                // Hitung potongan absen dari hari alpha
                $absenAlpha = Absensi::where('karyawan_id', $karyawan->id)
                    ->whereYear('tanggal', $periode->year)
                    ->whereMonth('tanggal', $periode->month)
                    ->where('status', 'alpha')
                    ->count();
                $potonganAbsen = $absenAlpha * ($karyawan->jabatan->gaji_pokok / 25);

                // Ambil komponen gaji dari database komponen_gaji
                $komponenPenambahan = DB::table('komponen_gaji')->where('jenis', 'penambahan')->sum('jumlah');
                $komponenPotongan = DB::table('komponen_gaji')->where('jenis', 'potongan')->sum('jumlah');

                $data = $request->all();
                $data['gaji_pokok'] = $karyawan->jabatan->gaji_pokok;
                $data['tunjangan_jabatan'] = $karyawan->jabatan->tunjangan_jabatan;
                $data['tunjangan_transport'] = $karyawan->jabatan->tunjangan_transport;
                $data['tunjangan_makan'] = $karyawan->jabatan->tunjangan_makan;
                $data['tunjangan_lainnya'] = ($data['tunjangan_lainnya'] ?? 0) + $komponenPenambahan;
                $data['potongan_absen'] = $potonganAbsen;
                $data['potongan_lainnya'] = ($data['potongan_lainnya'] ?? 0) + $komponenPotongan;
                $data['total_gaji'] = $data['gaji_pokok'] + $data['tunjangan_jabatan'] + $data['tunjangan_transport']
                    + $data['tunjangan_makan'] + $data['tunjangan_lainnya'] - $data['potongan_absen'] - $data['potongan_lainnya'];
                $data['status'] = 'pending';

                Penggajian::create($data);

                Log::info('Payroll calculated and saved successfully', [
                    'karyawan_id' => $karyawan->id,
                    'periode' => $periode->format('Y-m'),
                    'total_gaji' => $data['total_gaji'],
                    'processed_by' => auth()->id()
                ]);

                return redirect()->route('penggajian.index')->with('success', 'Penggajian berhasil dihitung dan disimpan');
            });
        } catch (\Exception $e) {
            Log::error('Payroll calculation failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memproses gaji: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Penggajian $penggajian)
    {
        $penggajian->load('karyawan.jabatan', 'karyawan.satuanKerja');
        return view('penggajian.show', compact('penggajian'));
    }

    public function edit(Penggajian $penggajian)
    {
        $karyawan = Karyawan::with('jabatan')->orderBy('nama_lengkap')->get();
        return view('penggajian.edit', compact('penggajian', 'karyawan'));
    }

    public function update(Request $request, Penggajian $penggajian)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|date',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'required|numeric|min:0',
            'tunjangan_transport' => 'required|numeric|min:0',
            'tunjangan_makan' => 'required|numeric|min:0',
            'tunjangan_lainnya' => 'nullable|numeric|min:0',
            'potongan_absen' => 'nullable|numeric|min:0',
            'potongan_lainnya' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,dibayar',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->all();
        $data['total_gaji'] = $data['gaji_pokok'] + $data['tunjangan_jabatan'] + $data['tunjangan_transport']
            + $data['tunjangan_makan'] + ($data['tunjangan_lainnya'] ?? 0) - ($data['potongan_absen'] ?? 0) - ($data['potongan_lainnya'] ?? 0);
        $penggajian->update($data);
        return redirect()->route('penggajian.index')->with('success', 'Penggajian berhasil diperbarui');
    }

    public function destroy(Penggajian $penggajian)
    {
        if ($penggajian->slip_gaji) {
            Storage::disk('public')->delete($penggajian->slip_gaji);
        }
        $penggajian->delete();
        return redirect()->route('penggajian.index')->with('success', 'Penggajian berhasil dihapus');
    }

    public function export(Request $request)
    {
        $periode = $request->periode;
        $nama = 'data-penggajian';
        if ($periode) $nama .= '-' . $periode;
        $nama .= '.xlsx';
        return Excel::download(new PenggajianExport($periode), $nama);
    }

    public function markPaid(Penggajian $penggajian)
    {
        $penggajian->update(['status' => 'dibayar']);
        return back()->with('success', 'Penggajian ditandai sudah dibayar');
    }

    public function printSlip(Penggajian $penggajian)
    {
        $penggajian->load('karyawan.jabatan', 'karyawan.satuanKerja');
        return view('penggajian.slip', compact('penggajian'));
    }
}