@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Detail Tugas</h1>
    <p class="text-gray-600 mt-1">Informasi lengkap tugas</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $tugas->judul }}</h2>
        <dl class="grid grid-cols-1 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $tugas->deskripsi ?: '-' }}</dd>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Ditugaskan Oleh</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $tugas->assignedBy?->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Ditugaskan Kepada</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $tugas->assignedTo?->name }}</dd>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Tenggat</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $tugas->tanggal_tenggat ? \Carbon\Carbon::parse($tugas->tanggal_tenggat)->format('d F Y') : '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @php
                            $statusClass = ['baru'=>'bg-blue-100 text-blue-800','diproses'=>'bg-yellow-100 text-yellow-800','selesai'=>'bg-green-100 text-green-800','terlambat'=>'bg-red-100 text-red-800'];
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$tugas->status] }}">
                            {{ ucfirst($tugas->status) }}
                        </span>
                    </dd>
                </div>
            </div>
            @if($tugas->bukti_penyelesaian)
            <div>
                <dt class="text-sm font-medium text-gray-500">Bukti Penyelesaian</dt>
                <dd class="mt-1"><a href="{{ asset('storage/' . $tugas->bukti_penyelesaian) }}" target="_blank" class="text-blue-600 hover:text-blue-900">Lihat Bukti</a></dd>
            </div>
            @endif
            @if($tugas->catatan)
            <div>
                <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $tugas->catatan }}</dd>
            </div>
            @endif
        </dl>
    </div>
    <div class="p-6 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status Tugas</h3>
        <form method="POST" action="{{ route('tasks.updateStatus', $tugas) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="input-field mt-1">
                        @foreach(['baru'=>'Baru','diproses'=>'Diproses','selesai'=>'Selesai','terlambat'=>'Terlambat'] as $key => $label)
                            <option value="{{ $key }}" {{ $tugas->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="bukti_penyelesaian" class="block text-sm font-medium text-gray-700">Bukti Penyelesaian</label>
                    <input id="bukti_penyelesaian" name="bukti_penyelesaian" type="file" accept=".pdf,.jpg,.jpeg,.png" class="input-field mt-1">
                </div>
            </div>
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea id="catatan" name="catatan" rows="2" class="input-field mt-1">{{ old('catatan', $tugas->catatan) }}</textarea>
            </div>
            <button type="submit" class="btn-primary">Update Status</button>
        </form>
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
        <a href="{{ route('tasks.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('tasks.edit', $tugas) }}" class="btn-primary">Edit</a>
    </div>
</div>
@endsection
