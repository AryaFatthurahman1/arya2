<!DOCTYPE html>
<html lang="id" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS - @yield('title', 'Dashboard')</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/ts/main.tsx'])
</head>
<body class="font-sans text-gray-800 antialiased overflow-hidden">
    <div class="flex h-screen bg-gray-50">
        
        <!-- Sidebar (Glassmorphism + Gradient) -->
        <aside class="w-72 flex-shrink-0 bg-gradient-to-b from-white/90 via-indigo-50/40 to-white/80 backdrop-blur-xl border-r border-indigo-100/60 shadow-[4px_0_32px_rgba(99,102,241,0.08)] flex flex-col z-20 transition-all duration-300 relative overflow-hidden">
            <!-- Decorative gradient orb -->
            <div class="absolute -top-20 -right-20 w-60 h-60 rounded-full bg-gradient-to-br from-indigo-300/20 to-purple-300/20 blur-3xl -z-0 pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-60 h-60 rounded-full bg-gradient-to-br from-blue-300/15 to-indigo-300/15 blur-3xl -z-0 pointer-events-none"></div>

            <!-- Logo Area -->
            <div class="h-20 flex items-center px-8 border-b border-indigo-100/60 relative z-10">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-blue-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-indigo-300/50 ring-2 ring-white/40">
                    HR
                </div>
                <div class="ml-3 overflow-hidden">
                    <span class="block font-extrabold text-lg bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-blue-700 tracking-tight leading-tight">HRIS IT/IJK</span>
                    <span class="block text-[10px] font-semibold text-indigo-500/80 uppercase tracking-widest">Human Resources</span>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-8 scrollbar-hide">
                
                <!-- Section: Main -->
                <div>
                    <p class="px-4 text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-3">Menu Utama</p>
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard.welcome') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard') || request()->is('dashboard/analytics*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-gauge w-5 text-center mr-3 {{ request()->is('dashboard') || request()->is('dashboard/analytics*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('analytics.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/analytics*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-chart-line w-5 text-center mr-3 {{ request()->is('dashboard/analytics*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Analitik
                        </a>
                    </nav>
                </div>

                <!-- Section: Master Data -->
                <div>
                    <p class="px-4 text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-3">Data Master</p>
                    <nav class="space-y-1">
                        <a href="{{ route('karyawan.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/employees*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-users w-5 text-center mr-3 {{ request()->is('dashboard/employees*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Karyawan
                        </a>
                        <a href="{{ route('jabatan.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/master/jabatan*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-briefcase w-5 text-center mr-3 {{ request()->is('dashboard/master/jabatan*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Jabatan
                        </a>
                        <a href="{{ route('satuan-kerja.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/master/satuan-kerja*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-building w-5 text-center mr-3 {{ request()->is('dashboard/master/satuan-kerja*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Satuan Kerja
                        </a>
                    </nav>
                </div>

                <!-- Section: Operational -->
                <div>
                    <p class="px-4 text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-3">Operasional</p>
                    <nav class="space-y-1">
                        <a href="{{ route('absensi.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/attendance*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-calendar-check w-5 text-center mr-3 {{ request()->is('dashboard/attendance*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Absensi
                        </a>
                        <a href="{{ route('leaves.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/leaves*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-file-medical w-5 text-center mr-3 {{ request()->is('dashboard/leaves*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Pengajuan Izin
                        </a>
                        <a href="{{ route('tasks.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/tasks*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-tasks w-5 text-center mr-3 {{ request()->is('dashboard/tasks*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Penugasan
                        </a>
                        <a href="{{ route('penilaian.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/performance*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-star w-5 text-center mr-3 {{ request()->is('dashboard/performance*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Penilaian
                        </a>
                        <a href="{{ route('penggajian.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/payroll*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-money-check-dollar w-5 text-center mr-3 {{ request()->is('dashboard/payroll*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Penggajian
                        </a>
                        <a href="{{ route('dokumen.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard/documents*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-folder-open w-5 text-center mr-3 {{ request()->is('dashboard/documents*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Dokumen
                        </a>
                    </nav>
                </div>

                <!-- Section: Settings -->
                <div>
                    <p class="px-4 text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-3">Pengaturan</p>
                    <nav class="space-y-1">
                        <a href="{{ route('profile.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('profile*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white/60 hover:text-indigo-700' }}">
                            <i class="fa-solid fa-user-gear w-5 text-center mr-3 {{ request()->is('profile*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }} transition-colors"></i>
                            Profil Saya
                        </a>
                    </nav>
                </div>
            </div>

            <!-- User Profile (Bottom) -->
            <div class="p-4 border-t border-indigo-100/60 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 relative z-10" x-data="{ open: false }">
                <div class="flex items-center cursor-pointer relative" @click="open = !open">
                    <div class="relative">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6366f1&color=fff' }}" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white shadow-md object-cover ring-2 ring-indigo-200/50">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="ml-3 overflow-hidden flex-1">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-indigo-600/70 truncate font-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-up text-xs text-indigo-400 transition-transform" :class="{ 'rotate-180': open }"></i>
                </div>
                
                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute bottom-full left-0 right-0 mb-2 mx-4 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-user text-gray-400 w-4 text-center"></i> Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fa-solid fa-sign-out-alt w-4 text-center"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-screen relative overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="h-20 flex-shrink-0 bg-gradient-to-r from-white/70 via-indigo-50/40 to-white/70 backdrop-blur-md border-b border-indigo-100/60 px-8 flex items-center justify-between z-10 sticky top-0 relative">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-blue-700">@yield('title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-500 mt-0.5">@yield('subtitle', 'Selamat datang di HRIS IT/IJK')</p>
                </div>

                <div class="flex items-center space-x-6">
                    <!-- Live Clock -->
                    <div id="live-clock-root"></div>

                    <!-- Notifications -->
                    <div id="notification-bell-root"></div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-8 relative">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-gradient-to-br from-blue-200/40 to-indigo-200/40 blur-3xl -z-10 pointer-events-none animate-pulse"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-gradient-to-br from-purple-200/40 to-blue-200/40 blur-3xl -z-10 pointer-events-none"></div>
                <div class="absolute top-1/2 left-1/2 -ml-32 -mt-32 w-64 h-64 rounded-full bg-gradient-to-br from-indigo-100/30 to-blue-100/30 blur-3xl -z-10 pointer-events-none"></div>
                
                <!-- Alerts -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-start shadow-sm animate-fade-in-down">
                        <i class="fa-solid fa-circle-check mt-0.5 mr-3 text-green-500"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-start shadow-sm animate-fade-in-down">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 mr-3 text-red-500"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @yield('content')
            </div>
            
        </main>
    </div>
    
    <!-- Alpine.js for some simple interactions (if needed) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.x.x/dist/chart.umd.min.js"></script>
    @stack('scripts')
    
    <script>
    function fetchNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }
    
    function markAllAsRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        .then(response => response.json())
        .then(data => {
            this.unreadCount = 0;
            this.open = false;
        })
        .catch(error => console.error('Error marking notifications as read:', error));
    }
    
    // Auto-refresh notifications every 30 seconds
    setInterval(() => {
        if (typeof fetchNotifications === 'function') {
            fetchNotifications();
        }
    }, 30000);
    </script>
</body>
</html>
