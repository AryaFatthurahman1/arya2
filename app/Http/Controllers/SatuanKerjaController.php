<?php

namespace App\Http\Controllers;

use App\Models\SatuanKerja;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SatuanKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view satuan-kerja')->only(['index', 'show']);
        $this->middleware('permission:create satuan-kerja')->only(['create', 'store']);
        $this->middleware('permission:edit satuan-kerja')->only(['edit', 'update']);
        $this->middleware('permission:delete satuan-kerja')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = SatuanKerja::query();
        if ($request->has('search')) {
            $query->where('nama_satuan_kerja', 'like', '%' . $request->search . '%');
        }
        $satuanKerja = $query->with('kepalaSatuanKerja')->paginate(10);
        return view('satuan-kerja.index', compact('satuanKerja'));
    }

    public function create()
    {
        $karyawan = Karyawan::all();
        return view('satuan-kerja.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_satuan_kerja' => 'required|string|max:255|unique:satuan_kerja,nama_satuan_kerja',
            'kepala_satuan_kerja_id' => 'nullable|exists:karyawan,id',
            'lokasi' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        SatuanKerja::create($request->all());
        return redirect()->route('satuan-kerja.index')->with('success', 'Satuan kerja berhasil ditambahkan');
    }

    public function show(SatuanKerja $satuanKerja)
    {
        $satuanKerja->load(['karyawan', 'kepalaSatuanKerja']);
        return view('satuan-kerja.show', compact('satuanKerja'));
    }

    public function edit(SatuanKerja $satuanKerja)
    {
        $karyawan = Karyawan::all();
        return view('satuan-kerja.edit', compact('satuanKerja', 'karyawan'));
    }

    public function update(Request $request, SatuanKerja $satuanKerja)
    {
        $validator = Validator::make($request->all(), [
            'nama_satuan_kerja' => 'required|string|max:255|unique:satuan_kerja,nama_satuan_kerja,' . $satuanKerja->id,
            'kepala_satuan_kerja_id' => 'nullable|exists:karyawan,id',
            'lokasi' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $satuanKerja->update($request->all());
        return redirect()->route('satuan-kerja.index')->with('success', 'Satuan kerja berhasil diperbarui');
    }

    public function destroy(SatuanKerja $satuanKerja)
    {
        $satuanKerja->delete();
        return redirect()->route('satuan-kerja.index')->with('success', 'Satuan kerja berhasil dihapus');
    }
}