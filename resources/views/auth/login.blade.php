<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HRIS IT/IJK</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 25%, #312e81 50%, #4338ca 75%, #6366f1 100%); min-height: 100vh; }
        .login-card { background: rgba(255,255,255,0.07); backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.12); }
        .login-input { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: #fff; }
        .login-input::placeholder { color: rgba(255,255,255,0.4); }
        .login-input:focus { background: rgba(255,255,255,0.1); border-color: #818cf8; box-shadow: 0 0 0 3px rgba(129,140,248,0.25); }
        .login-btn { background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7); transition: all 0.3s; }
        .login-btn:hover { background: linear-gradient(135deg, #4f46e5, #7c3aed, #9333ea); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.4); }
        .floating-orb { position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.3; animation: float 8s ease-in-out infinite; }
        @keyframes float { 0%,100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-20px) scale(1.05); } }
        .gradient-text { background: linear-gradient(135deg, #c7d2fe, #e9d5ff, #ddd6fe); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .label-text { color: rgba(255,255,255,0.7); }
    </style>
</head>
<body class="flex items-center justify-center overflow-hidden relative">
    <div class="floating-orb w-72 h-72 bg-indigo-500 top-[-10%] left-[-5%]" style="animation-delay:0s"></div>
    <div class="floating-orb w-96 h-96 bg-purple-600 bottom-[-15%] right-[-10%]" style="animation-delay:2s"></div>
    <div class="floating-orb w-48 h-48 bg-blue-400 top-[60%] left-[10%]" style="animation-delay:4s"></div>

    <div class="relative z-10 w-full max-w-md px-4">
        <div class="login-card rounded-3xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold gradient-text">HRIS IT/IJK</h1>
                <p class="text-sm mt-2" style="color:rgba(255,255,255,0.5)">Sistem Manajemen Karyawan</p>
            </div>

            @if($errors->any())
            <div class="mb-6 p-3 rounded-xl bg-red-500/20 border border-red-500/30 text-red-200 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="login" class="block text-sm font-medium label-text mb-1.5">Nama atau Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5" style="color:rgba(255,255,255,0.4)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="login" name="login" type="text" required
                            class="login-input w-full rounded-xl pl-11 pr-4 py-3 outline-none transition-all duration-300"
                            placeholder="Masukkan nama atau email" value="{{ old('login') }}">
                    </div>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium label-text mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5" style="color:rgba(255,255,255,0.4)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="login-input w-full rounded-xl pl-11 pr-4 py-3 outline-none transition-all duration-300"
                            placeholder="Masukkan password">
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input name="remember" type="checkbox" class="w-4 h-4 rounded border-white/20 bg-white/10 text-indigo-500 focus:ring-indigo-400">
                        <span class="text-sm label-text">Ingat saya</span>
                    </label>
                </div>
                <button type="submit" class="login-btn w-full py-3 rounded-xl text-white font-semibold text-sm shadow-lg">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs" style="color:rgba(255,255,255,0.35)">
                    IT/IJK &mdash; Pengembangan Aplikasi Web Management Projek Interfaces
                </p>
            </div>
        </div>
    </div>
</body>
</html>
