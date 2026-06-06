@extends('layouts.app')

@section('title', 'Absen Online')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Absen Online</h1>
    <p class="text-gray-600 mt-1">Lakukan absensi langsung melalui website</p>
</div>

<div class="card max-w-lg">
    <div class="p-6">
        <form method="POST" action="{{ route('absensi.clock-in') }}" class="space-y-6">
            @csrf
            <div>
                <label for="karyawan_id" class="block text-sm font-medium text-gray-700">Pilih Karyawan</label>
                <select id="karyawan_id" name="karyawan_id" required class="input-field mt-1">
                    <option value="">Pilih Karyawan</option>
                    @foreach ($karyawan as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_lengkap }} - {{ $item->nik }}</option>
                    @endforeach
                </select>
                @error('karyawan_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <button type="submit" class="btn-primary w-full text-center py-3 text-lg">
                    <i class="fa-solid fa-fingerprint me-2"></i> Absen Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
