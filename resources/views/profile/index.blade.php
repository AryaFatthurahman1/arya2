@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <div class="relative group">
                <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-4xl font-bold overflow-hidden shadow-lg shadow-indigo-200">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        <span>{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    @endif
                </div>
                <label for="photo-upload" class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                    <i class="fa-solid fa-camera text-white text-xl"></i>
                </label>
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                <p class="text-gray-500">{{ auth()->user()->email }}</p>
                <div class="flex flex-wrap gap-2 mt-3 justify-center sm:justify-start">
                    @foreach(auth()->user()->getRoleNames() as $role)
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-semibold border border-indigo-100">
                            <i class="fa-solid fa-shield-halved text-xs mr-1"></i>{{ ucfirst($role) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Edit Profil</h2>
            <p class="text-sm text-gray-500 mb-6">Perbarui informasi personal Anda</p>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PATCH')
                <input type="file" name="photo" id="photo-upload" class="hidden" accept="image/*" onchange="this.form.submit()">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="input-field">
                    @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="input-field">
                    @error('email')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="input-field" placeholder="Nomor telepon">
                    @error('phone')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                    <textarea name="address" rows="2" class="input-field" placeholder="Alamat lengkap">{{ old('address', auth()->user()->address) }}</textarea>
                    @error('address')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fa-solid fa-save mr-2"></i>Simpan Perubahan</button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Ubah Password</h2>
            <p class="text-sm text-gray-500 mb-6">Ganti kata sandi akun Anda</p>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Saat Ini</label>
                    <input type="password" name="current_password" class="input-field" placeholder="Masukkan password saat ini" required>
                    @error('current_password')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                    <input type="password" name="password" class="input-field" placeholder="Min. 8 karakter, huruf & angka" required>
                    @error('password')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="input-field" placeholder="Ulangi password baru" required>
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fa-solid fa-lock mr-2"></i>Ubah Password</button>
            </form>
        </div>
    </div>

    @if(auth()->user()->karyawan)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Data Karyawan</h2>
                <p class="text-sm text-gray-500">Lihat detail data kepegawaian Anda</p>
            </div>
            <a href="{{ route('karyawan.show', auth()->user()->karyawan) }}" class="btn-secondary"><i class="fa-solid fa-arrow-right mr-2"></i>Lihat Detail</a>
        </div>
    </div>
    @endif
</div>
@endsection
