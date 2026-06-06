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
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-800 antialiased overflow-hidden">
    <div class="flex h-screen bg-gray-50">
        
        <!-- Sidebar (Glassmorphism) -->
        <aside class="w-72 flex-shrink-0 bg-white/80 backdrop-blur-xl border-r border-gray-200/60 shadow-[4px_0_24px_rgba(0,0,0,0.02)] flex flex-col z-20 transition-all duration-300">
            <!-- Logo Area -->
            <div class="h-20 flex items-center px-8 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-200">
                    HR
                </div>
                <span class="ml-3 font-bold text-xl bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-600 tracking-tight">SyncHRIS</span>
            </div>

            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-8 scrollbar-hide">
                
                <!-- Section: Main -->
                <div>
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Main Menu</p>
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dashboard*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-gauge w-5 text-center mr-3 {{ request()->is('dashboard*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Dashboard
                        </a>
                    </nav>
                </div>

                <!-- Section: Master Data -->
                <div>
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Master Data</p>
                    <nav class="space-y-1">
                        <a href="{{ route('karyawan.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('karyawan*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-users w-5 text-center mr-3 {{ request()->is('karyawan*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Karyawan
                        </a>
                        <a href="{{ route('jabatan.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('jabatan*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-briefcase w-5 text-center mr-3 {{ request()->is('jabatan*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Jabatan
                        </a>
                        <a href="{{ route('satuan-kerja.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('satuan-kerja*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-building w-5 text-center mr-3 {{ request()->is('satuan-kerja*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Satuan Kerja
                        </a>
                    </nav>
                </div>

                <!-- Section: Operational -->
                <div>
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Operasional</p>
                    <nav class="space-y-1">
                        <a href="{{ route('absensi.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('absensi*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-calendar-check w-5 text-center mr-3 {{ request()->is('absensi*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Absensi
                        </a>
                        <a href="{{ route('leaves.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('leaves*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-file-medical w-5 text-center mr-3 {{ request()->is('izin*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Pengajuan Izin
                        </a>
                        <a href="{{ route('tasks.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('tasks*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-tasks w-5 text-center mr-3 {{ request()->is('tugas*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Penugasan
                        </a>
                        <a href="{{ route('penilaian.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('penilaian*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-star w-5 text-center mr-3 {{ request()->is('penilaian*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Penilaian
                        </a>
                        <a href="{{ route('penggajian.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('penggajian*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-money-check-dollar w-5 text-center mr-3 {{ request()->is('penggajian*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Penggajian
                        </a>
                        <a href="{{ route('dokumen.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('dokumen*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-folder-open w-5 text-center mr-3 {{ request()->is('dokumen*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Dokumen
                        </a>
                    </nav>
                </div>

                <!-- Section: Settings -->
                <div>
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Pengaturan</p>
                    <nav class="space-y-1">
                        <a href="{{ route('profile.index') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->is('profile*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100/50 hover:text-gray-900' }}">
                            <i class="fa-solid fa-user-gear w-5 text-center mr-3 {{ request()->is('profile*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors"></i>
                            Profil Saya
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- User Profile (Bottom) -->
            <div class="p-4 border-t border-gray-100 bg-gray-50/50" x-data="{ open: false }">
                <div class="flex items-center cursor-pointer relative" @click="open = !open">
                    <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6366f1&color=fff' }}" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white shadow-sm object-cover">
                    <div class="ml-3 overflow-hidden flex-1">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-up text-xs text-gray-400 transition-transform" :class="{ 'rotate-180': open }"></i>
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
            <header class="h-20 flex-shrink-0 bg-white/60 backdrop-blur-md border-b border-gray-200/60 px-8 flex items-center justify-between z-10 sticky top-0">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">@yield('title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div x-data="{ open: false, notifications: [], unreadCount: 0 }" class="relative" x-init="fetchNotifications()">
                        <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors bg-white rounded-full shadow-sm border border-gray-100 hover:border-indigo-100">
                            <i class="fa-regular fa-bell"></i>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center border-2 border-white"></span>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50" style="display: none;">
                            <div class="p-4 border-b border-gray-100">
                                <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500">
                                        <p>Tidak ada notifikasi baru</p>
                                    </div>
                                </template>
                                <template x-for="notification in notifications" :key="notification.id">
                                    <a :href="notification.url" class="block p-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                        <div class="flex items-start">
                                            <div :class="`p-2 rounded-lg bg-${notification.color}-100 mr-3`">
                                                <i :class="`fa-solid ${notification.icon} text-${notification.color}-600`"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                                <p class="text-sm text-gray-500 mt-1" x-text="notification.message"></p>
                                                <p class="text-xs text-gray-400 mt-1" x-text="notification.time"></p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                            <div class="p-3 border-t border-gray-100">
                                <button @click="markAllAsRead()" class="w-full text-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Tandai semua sudah dibaca
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-8 relative">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-blue-100/50 blur-3xl -z-10 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-purple-100/50 blur-3xl -z-10 pointer-events-none"></div>
                
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
