@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Tugas</h1>
    <p class="text-gray-600 mt-1">Perbarui data tugas</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('tasks.update', $tugas) }}" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul Tugas</label>
                    <input id="judul" name="judul" type="text" required class="input-field mt-1" value="{{ old('judul', $tugas->judul) }}">
                    @error('judul')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="input-field mt-1">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                </div>
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700">Ditugaskan Kepada</label>
                    <select id="assigned_to" name="assigned_to" required class="input-field mt-1">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $tugas->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tanggal_tenggat" class="block text-sm font-medium text-gray-700">Tanggal Tenggat</label>
                    <input id="tanggal_tenggat" name="tanggal_tenggat" type="date" required class="input-field mt-1" value="{{ old('tanggal_tenggat', $tugas->tanggal_tenggat) }}">
                    @error('tanggal_tenggat')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required class="input-field mt-1">
                        @foreach(['baru'=>'Baru','diproses'=>'Diproses','selesai'=>'Selesai','terlambat'=>'Terlambat'] as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $tugas->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('tasks.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
