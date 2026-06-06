<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PenilaianController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view penilaian')->only(['index', 'show']);
        $this->middleware('permission:create penilaian')->only(['create', 'store']);
        $this->middleware('permission:edit penilaian')->only(['edit', 'update']);
        $this->middleware('permission:delete penilaian')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Penilaian::with(['karyawan', 'dinilaiBy']);
        if ($request->has('karyawan_id') && $request->karyawan_id) {
            $query->where('karyawan_id', $request->karyawan_id);
        }
        $penilaian = $query->latest('periode')->paginate(15);
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('penilaian.index', compact('penilaian', 'karyawan'));
    }

    public function create()
    {
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('penilaian.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|date',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_kualitas' => 'required|numeric|min:0|max:100',
            'nilai_tanggung_jawab' => 'required|numeric|min:0|max:100',
            'nilai_komunikasi' => 'required|numeric|min:0|max:100',
            'nilai_inisiatif' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->all();
        $data['total_nilai'] = round((
            $data['nilai_disiplin'] + $data['nilai_kualitas'] + $data['nilai_tanggung_jawab']
            + $data['nilai_komunikasi'] + $data['nilai_inisiatif']
        ) / 5, 2);
        $data['dinilai_by'] = auth()->id();
        Penilaian::create($data);
        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan');
    }

    public function show(Penilaian $penilaian)
    {
        $penilaian->load(['karyawan', 'dinilaiBy']);
        return view('penilaian.show', compact('penilaian'));
    }

    public function edit(Penilaian $penilaian)
    {
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('penilaian.edit', compact('penilaian', 'karyawan'));
    }

    public function update(Request $request, Penilaian $penilaian)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|date',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_kualitas' => 'required|numeric|min:0|max:100',
            'nilai_tanggung_jawab' => 'required|numeric|min:0|max:100',
            'nilai_komunikasi' => 'required|numeric|min:0|max:100',
            'nilai_inisiatif' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->all();
        $data['total_nilai'] = round((
            $data['nilai_disiplin'] + $data['nilai_kualitas'] + $data['nilai_tanggung_jawab']
            + $data['nilai_komunikasi'] + $data['nilai_inisiatif']
        ) / 5, 2);
        $penilaian->update($data);
        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil diperbarui');
    }

    public function destroy(Penilaian $penilaian)
    {
        $penilaian->delete();
        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil dihapus');
    }
}