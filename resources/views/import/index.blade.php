@extends('layouts.app')

@section('title', 'Import Data')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Import Data</h1>
    <p class="text-gray-600 mt-1">Import data dari file Excel</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Import Karyawan</h2>
        <form method="POST" action="{{ route('karyawan.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Excel Karyawan</label>
                <input type="file" name="file" accept=".xlsx,.xls" class="input-field" required>
            </div>
            <a href="{{ route('karyawan.download-template') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Download Template</a>
            <button type="submit" class="btn-primary w-full">Import</button>
        </form>
    </div>

    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Import Absensi</h2>
        <form method="POST" action="{{ route('absensi.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Excel Absensi</label>
                <input type="file" name="file" accept=".xlsx,.xls" class="input-field" required>
            </div>
            <button type="submit" class="btn-primary w-full">Import</button>
        </form>
    </div>
</div>
@endsection