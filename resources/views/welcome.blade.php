<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SyncHRIS — Human Resource Information System</title>
    <meta name="description" content="Sistem Informasi Manajemen Sumber Daya Manusia Berbasis Laravel. Kelola karyawan, absensi, penggajian, dan kinerja dalam satu platform terintegrasi.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 40%, #24243e 100%);
            position: relative;
            overflow: hidden;
        }
        .gradient-bg::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(ellipse at 20% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(139, 92, 246, 0.12) 0%, transparent 50%),
                        radial-gradient(ellipse at 60% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(-2%, 3%) rotate(1deg); }
            50% { transform: translate(1%, -2%) rotate(-1deg); }
            75% { transform: translate(3%, 1%) rotate(0.5deg); }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .glow-btn {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a78bfa 100%);
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.4), 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .glow-btn:hover {
            box-shadow: 0 0 50px rgba(99, 102, 241, 0.6), 0 8px 25px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }
        .stat-number {
            background: linear-gradient(135deg, #a78bfa, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: drift 15s infinite ease-in-out;
        }
        @keyframes drift {
            0%, 100% { transform: translate(0, 0); opacity: 0.3; }
            50% { transform: translate(30px, -30px); opacity: 0.7; }
        }
        .fade-up { animation: fadeUp 0.8s ease-out forwards; opacity: 0; }
        .fade-up-delay-1 { animation-delay: 0.15s; }
        .fade-up-delay-2 { animation-delay: 0.3s; }
        .fade-up-delay-3 { animation-delay: 0.45s; }
        .fade-up-delay-4 { animation-delay: 0.6s; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen text-white">
    <!-- Floating particles -->
    <div class="particle" style="width:300px;height:300px;top:10%;left:70%;"></div>
    <div class="particle" style="width:200px;height:200px;top:60%;left:10%;animation-delay:-5s;"></div>
    <div class="particle" style="width:150px;height:150px;top:80%;left:80%;animation-delay:-10s;"></div>

    <!-- Navigation -->
    <nav class="relative z-10 flex items-center justify-between px-8 lg:px-16 py-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/30">
                HR
            </div>
            <span class="text-xl font-bold tracking-tight">SyncHRIS</span>
        </div>
        <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-sm font-semibold hover:bg-white/20 transition-all duration-300">
            <i class="fa-solid fa-right-to-bracket mr-2"></i>Masuk
        </a>
    </nav>

    <!-- Hero Section -->
    <section class="relative z-10 px-8 lg:px-16 pt-16 pb-24 max-w-7xl mx-auto">
        <div class="text-center max-w-4xl mx-auto">
            <div class="fade-up inline-flex items-center px-4 py-2 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-sm font-medium mb-8 backdrop-blur-sm">
                <i class="fa-solid fa-sparkles mr-2"></i>
                Enterprise-Grade HR Platform v2.0
            </div>
            <h1 class="fade-up fade-up-delay-1 text-5xl lg:text-7xl font-black tracking-tight leading-tight mb-6">
                Kelola SDM Anda
                <br>
                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Dengan Lebih Cerdas
                </span>
            </h1>
            <p class="fade-up fade-up-delay-2 text-lg lg:text-xl text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Sistem Informasi Manajemen Sumber Daya Manusia terintegrasi untuk manajemen karyawan, absensi, penggajian, penilaian kinerja, dan penugasan dalam satu platform yang powerful.
            </p>
            <div class="fade-up fade-up-delay-3 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="glow-btn px-8 py-4 rounded-2xl text-white font-bold text-base tracking-wide">
                    <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>Masuk ke Dashboard
                </a>
                <a href="#fitur" class="px-8 py-4 rounded-2xl bg-white/5 border border-white/10 font-semibold text-base hover:bg-white/10 transition-all duration-300 backdrop-blur-sm">
                    <i class="fa-solid fa-eye mr-2"></i>Lihat Fitur
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="fade-up fade-up-delay-4 grid grid-cols-2 md:grid-cols-4 gap-4 mt-20 max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-6 text-center">
                <p class="stat-number text-3xl font-black">7+</p>
                <p class="text-gray-400 text-sm mt-1">Modul Terintegrasi</p>
            </div>
            <div class="glass-card rounded-2xl p-6 text-center">
                <p class="stat-number text-3xl font-black">3</p>
                <p class="text-gray-400 text-sm mt-1">Level Akses (RBAC)</p>
            </div>
            <div class="glass-card rounded-2xl p-6 text-center">
                <p class="stat-number text-3xl font-black">AES</p>
                <p class="text-gray-400 text-sm mt-1">256-bit Enkripsi</p>
            </div>
            <div class="glass-card rounded-2xl p-6 text-center">
                <p class="stat-number text-3xl font-black">∞</p>
                <p class="text-gray-400 text-sm mt-1">Import Excel</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="relative z-10 px-8 lg:px-16 py-24 max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">Fitur Lengkap & Terintegrasi</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk mengelola sumber daya manusia perusahaan dalam satu platform yang modern dan aman.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-users text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Manajemen Karyawan</h3>
                <p class="text-gray-400 text-sm leading-relaxed">CRUD lengkap data karyawan dengan enkripsi AES-256 untuk data sensitif. Import massal via Excel.</p>
            </div>
            <!-- Feature 2 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-calendar-check text-green-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Absensi Online & Import</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Clock-in/out real-time dengan GPS tracking. Import data absensi dari mesin fingerprint via Excel.</p>
            </div>
            <!-- Feature 3 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-file-medical text-yellow-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Pengajuan Izin & Cuti</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Workflow approval multi-level dengan upload bukti dokumen (PDF, JPG, Word). Notifikasi status otomatis.</p>
            </div>
            <!-- Feature 4 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-list-check text-purple-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Penugasan Tugas</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Assign tugas ke karyawan, tracking status (baru → diproses → selesai), upload bukti penyelesaian.</p>
            </div>
            <!-- Feature 5 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-orange-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-star text-orange-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Penilaian Kinerja</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Evaluasi 5 indikator KPI (Disiplin, Kualitas, Tanggung Jawab, Komunikasi, Inisiatif) dengan skor otomatis.</p>
            </div>
            <!-- Feature 6 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-money-check-dollar text-emerald-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Penggajian Otomatis</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Kalkulasi gaji otomatis berbasis jabatan & kehadiran. Slip gaji digital, export ke Excel, cetak PDF.</p>
            </div>
            <!-- Feature 7 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-rose-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-shield-halved text-rose-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">RBAC & Keamanan</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Spatie Permission (Admin, Atasan, Karyawan). Rate limiting, CSRF protection, 2FA ready.</p>
            </div>
            <!-- Feature 8 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-file-excel text-cyan-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Import & Export Excel</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Maatwebsite Excel untuk import massal karyawan & absensi. Export data penggajian & laporan ke XLSX.</p>
            </div>
            <!-- Feature 9 -->
            <div class="glass-card rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-upload text-indigo-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Upload Multi-Format</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Upload file apa saja — gambar, PDF, Word, Excel, dokumen lainnya — dengan validasi tipe & ukuran file.</p>
            </div>
        </div>
    </section>

    <!-- Tech Stack -->
    <section class="relative z-10 px-8 lg:px-16 py-16 max-w-7xl mx-auto">
        <div class="glass-card rounded-3xl p-10 lg:p-14">
            <h2 class="text-2xl font-bold mb-8 text-center">Tech Stack & Arsitektur</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 text-center">
                <div class="p-4">
                    <i class="fa-brands fa-laravel text-3xl text-red-400 mb-2"></i>
                    <p class="text-sm text-gray-400">Laravel 11</p>
                </div>
                <div class="p-4">
                    <i class="fa-brands fa-php text-3xl text-indigo-400 mb-2"></i>
                    <p class="text-sm text-gray-400">PHP 8.3+</p>
                </div>
                <div class="p-4">
                    <i class="fa-solid fa-database text-3xl text-blue-400 mb-2"></i>
                    <p class="text-sm text-gray-400">MySQL 8.x</p>
                </div>
                <div class="p-4">
                    <i class="fa-brands fa-css3-alt text-3xl text-cyan-400 mb-2"></i>
                    <p class="text-sm text-gray-400">Tailwind CSS</p>
                </div>
                <div class="p-4">
                    <i class="fa-brands fa-js text-3xl text-yellow-400 mb-2"></i>
                    <p class="text-sm text-gray-400">Vite + JS</p>
                </div>
                <div class="p-4">
                    <i class="fa-brands fa-docker text-3xl text-sky-400 mb-2"></i>
                    <p class="text-sm text-gray-400">Docker / K8s</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Login CTA -->
    <section class="relative z-10 px-8 lg:px-16 py-16 max-w-7xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-4">Siap Mengelola Tim Anda?</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto">Masuk sekarang dengan akun default: <code class="bg-white/10 px-2 py-0.5 rounded text-indigo-300">admin@hr.test</code> / <code class="bg-white/10 px-2 py-0.5 rounded text-indigo-300">password</code></p>
        <a href="{{ route('login') }}" class="glow-btn inline-flex items-center px-10 py-4 rounded-2xl text-white font-bold text-lg">
            <i class="fa-solid fa-arrow-right-to-bracket mr-3"></i>Masuk ke Dashboard
        </a>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/5 px-8 lg:px-16 py-8 text-center text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} SyncHRIS — Pengembangan Aplikasi Web Manajemen Karyawan Berbasis Laravel</p>
        <p class="mt-1">Muhammad Arya Fatthurahman • HRIS Enterprise v2.0</p>
    </footer>
</body>
</html>
