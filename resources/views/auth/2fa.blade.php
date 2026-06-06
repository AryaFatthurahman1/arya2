<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi 2FA - HR Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="card max-w-md w-full p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Verifikasi Dua Faktor</h2>
            <p class="mt-2 text-gray-600">Masukkan kode OTP dari aplikasi autentikasi Anda</p>
        </div>
        <form class="space-y-6" method="POST" action="{{ route('2fa.verify') }}">
            @csrf
            <div>
                <label for="one_time_password" class="block text-sm font-medium text-gray-700">Kode OTP</label>
                <input id="one_time_password" name="one_time_password" type="text" inputmode="numeric" autocomplete="one-time-code" required class="input-field mt-1 text-center text-2xl tracking-widest" maxlength="6">
                @error('one_time_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn-primary w-full">Verifikasi</button>
        </form>
    </div>
</body>
</html>
