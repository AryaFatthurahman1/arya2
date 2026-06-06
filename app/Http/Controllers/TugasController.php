<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage tasks')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Tugas::with(['assignedBy', 'assignedTo']);
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        $tugas = $query->latest()->paginate(15);
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        return view('tugas.index', compact('tugas', 'karyawan'));
    }

    public function create()
    {
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        $users = User::orderBy('name')->get();
        return view('tugas.create', compact('karyawan', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'tanggal_tenggat' => 'required|date|after_or_equal:today',
            'status' => 'required|in:baru,diproses,selesai,terlambat',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        Tugas::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'assigned_to' => $request->assigned_to,
            'tanggal_tenggat' => $request->tanggal_tenggat,
            'status' => $request->status,
            'assigned_by' => auth()->id(),
        ]);
        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dibuat');
    }

    public function show(Tugas $tugas)
    {
        $tugas->load(['assignedBy', 'assignedTo']);
        return view('tugas.show', compact('tugas'));
    }

    public function edit(Tugas $tugas)
    {
        $users = User::orderBy('name')->get();
        return view('tugas.edit', compact('tugas', 'users'));
    }

    public function update(Request $request, Tugas $tugas)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'tanggal_tenggat' => 'required|date',
            'status' => 'required|in:baru,diproses,selesai,terlambat',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->all();
        if ($data['status'] == 'selesai' && $tugas->status != 'selesai') {
            $data['selesai_at'] = Carbon::now();
        }
        $tugas->update($data);
        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui');
    }

    public function updateStatus(Request $request, Tugas $tugas)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:baru,diproses,selesai,terlambat',
            'catatan' => 'nullable|string',
            'bukti_penyelesaian' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $data = $request->except('bukti_penyelesaian');
        if ($request->hasFile('bukti_penyelesaian')) {
            $data['bukti_penyelesaian'] = $request->file('bukti_penyelesaian')->store('tugas', 'public');
        }
        if ($data['status'] == 'selesai' && $tugas->status != 'selesai') {
            $data['selesai_at'] = Carbon::now();
        }
        $tugas->update($data);
        return back()->with('success', 'Status tugas berhasil diperbarui');
    }

    public function destroy(Tugas $tugas)
    {
        if ($tugas->bukti_penyelesaian) {
            Storage::disk('public')->delete($tugas->bukti_penyelesaian);
        }
        $tugas->delete();
        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus');
    }
}