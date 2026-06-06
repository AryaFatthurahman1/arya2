@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Dokumen</h1>
                <p class="text-gray-500 text-sm">Informasi lengkap dokumen</p>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="p-8 border-b border-gray-100">
            <div class="flex items-center gap-4">
                @php
                    $typeIcons = [
                        'application/pdf' => ['fa-file-pdf', 'text-red-500', 'bg-red-50'],
                        'application/msword' => ['fa-file-word', 'text-blue-500', 'bg-blue-50'],
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['fa-file-word', 'text-blue-500', 'bg-blue-50'],
                        'application/vnd.ms-excel' => ['fa-file-excel', 'text-emerald-500', 'bg-emerald-50'],
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['fa-file-excel', 'text-emerald-500', 'bg-emerald-50'],
                        'image/jpeg' => ['fa-file-image', 'text-purple-500', 'bg-purple-50'],
                        'image/png' => ['fa-file-image', 'text-purple-500', 'bg-purple-50'],
                    ];
                    $iconInfo = $typeIcons[$dokumen->file_type] ?? ['fa-file', 'text-gray-500', 'bg-gray-50'];
                @endphp
                <div class="w-16 h-16 rounded-2xl {{ $iconInfo[2] }} flex items-center justify-center">
                    <i class="fa-solid {{ $iconInfo[0] }} {{ $iconInfo[1] }} text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $dokumen->nama_dokumen }}</h2>
                    <p class="text-sm text-gray-500">{{ ucfirst($dokumen->tipe_dokumen) }}</p>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider"><i class="fa-solid fa-calendar mr-1 text-indigo-400"></i>Diupload</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $dokumen->created_at->format('d F Y H:i') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider"><i class="fa-solid fa-user mr-1 text-indigo-400"></i>Oleh</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $dokumen->uploader?->name ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider"><i class="fa-solid fa-user mr-1 text-indigo-400"></i>Karyawan</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $dokumen->karyawan?->nama_lengkap ?? 'Dokumen Perusahaan' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider"><i class="fa-solid fa-weight mr-1 text-indigo-400"></i>Ukuran File</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">
                        @php
                            $size = $dokumen->file_size;
                            echo $size >= 1048576 ? round($size / 1048576, 1) . ' MB' : ($size >= 1024 ? round($size / 1024, 1) . ' KB' : $size . ' B');
                        @endphp
                    </p>
                </div>
            </div>
            @if($dokumen->deskripsi)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider"><i class="fa-solid fa-align-left mr-1 text-indigo-400"></i>Deskripsi</p>
                <p class="mt-1 text-sm font-medium text-gray-900">{{ $dokumen->deskripsi }}</p>
            </div>
            @endif
        </div>

        <div class="px-8 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('dokumen.index') }}" class="btn-secondary"><i class="fa-solid fa-arrow-left mr-2"></i>Kembali</a>
            <a href="{{ route('dokumen.download', $dokumen) }}" class="btn-primary"><i class="fa-solid fa-download mr-2"></i>Download</a>
            @can('edit dokumen')
            <a href="{{ route('dokumen.edit', $dokumen) }}" class="btn-secondary"><i class="fa-solid fa-edit mr-2"></i>Edit</a>
            @endcan
        </div>
    </div>
</div>
@endsection
