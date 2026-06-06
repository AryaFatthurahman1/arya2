<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class JabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view jabatan')->only(['index', 'show']);
        $this->middleware('permission:create jabatan')->only(['create', 'store']);
        $this->middleware('permission:edit jabatan')->only(['edit', 'update']);
        $this->middleware('permission:delete jabatan')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Jabatan::query();
        if ($request->has('search')) {
            $query->where('nama_jabatan', 'like', '%' . $request->search . '%');
        }
        $jabatan = $query->paginate(10);
        return view('jabatan.index', compact('jabatan'));
    }

    public function create()
    {
        return view('jabatan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jabatan' => 'required|string|max:255|unique:jabatan,nama_jabatan',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'required|numeric|min:0',
            'tunjangan_transport' => 'required|numeric|min:0',
            'tunjangan_makan' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        Jabatan::create($request->all());
        Log::info('Jabatan created', ['user_id' => auth()->id(), 'nama' => $request->nama_jabatan]);
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function show(Jabatan $jabatan)
    {
        $jabatan->load('karyawan');
        return view('jabatan.show', compact('jabatan'));
    }

    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validator = Validator::make($request->all(), [
            'nama_jabatan' => 'required|string|max:255|unique:jabatan,nama_jabatan,' . $jabatan->id,
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'required|numeric|min:0',
            'tunjangan_transport' => 'required|numeric|min:0',
            'tunjangan_makan' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $jabatan->update($request->all());
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus');
    }
}