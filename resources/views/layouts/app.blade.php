<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIATA') }}</title>

        <!-- Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <!-- Alpine.js state untuk sidebar via attribute x-data -->
    <body class="font-sans antialiased bg-gray-100 text-gray-900 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar Navigation (Indigo-900) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-indigo-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-xl">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-center h-16 border-b border-indigo-800">
                <span class="text-2xl font-bold tracking-wider">SIATA</span>
            </div>

            <!-- Menu Links Berdasarkan Role -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <!-- Komentar: Active State ditentukan dari request()->routeIs('dashboard') -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                    <!-- Heroicon: Home -->
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                @if(Auth::user()->role == 'mahasiswa')
                    <!-- Menu khusus Mahasiswa -->
                    <a href="{{ route('skripsi.create') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('skripsi.*') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Pengajuan Skripsi
                    </a>
                    <a href="{{ route('bimbingan.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('bimbingan.*') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Logbook Bimbingan
                    </a>
                    <a href="{{ route('mahasiswa.sidang') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('mahasiswa.sidang*') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Info Sidang
                    </a>
                @elseif(Auth::user()->role == 'dosen')
                    <!-- Menu khusus Dosen -->
                    <a href="{{ route('dosen.bimbingan.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dosen.bimbingan.*') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Anak Wali / Bimbingan
                    </a>
                    
                    <a href="{{ route('dosen.sidang.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dosen.sidang.*') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Jadwal Menguji
                    </a>
                @elseif(Auth::user()->role == 'kaprodi')
                    <!-- Menu khusus Kaprodi -->
                    <a href="{{ route('kaprodi.validasi') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('kaprodi.validasi') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Validasi Judul
                    </a>
                @elseif(Auth::user()->role == 'admin')
                    <!-- Menu khusus Admin -->
                    <a href="{{ route('admin.sidang.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.sidang.*') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Jadwal Sidang
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white font-medium shadow-sm' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Manajemen User
                    </a>
                @endif
            </nav>

            <!-- Sidebar Footer (Logout) -->
            <div class="p-4 border-t border-indigo-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-indigo-200 transition-colors rounded-lg hover:bg-indigo-800 hover:text-white">
                        <!-- Heroicon: Logout -->
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Topbar Header -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 lg:justify-end shadow-sm z-10">
                <!-- Hamburger Menu Mobile -->
                <div class="flex items-center lg:hidden">
                    <button @click="sidebarOpen = true" class="text-gray-500 hover:text-indigo-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Right Side (Notifications & User) -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications Dropdown -->
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" class="relative p-2 text-gray-500 hover:text-indigo-600 focus:outline-none transition-colors rounded-full hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full border border-white">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>

                        <!-- Notif Dropdown Menu -->
                        <div x-show="notifOpen" @click.away="notifOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-80 mt-3 origin-top-right bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden" style="display: none;">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-700">Notifikasi</span>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="text-xs text-indigo-600 font-medium">{{ Auth::user()->unreadNotifications->count() }} Baru</span>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ is_null($notification->read_at) ? 'bg-blue-50/60' : '' }}">
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="text-sm font-bold {{ is_null($notification->read_at) ? 'text-gray-900' : 'text-gray-700' }}">{{ $notification->data['title'] }}</span>
                                            <span class="text-[10px] {{ is_null($notification->read_at) ? 'text-gray-500 font-medium' : 'text-gray-400' }}">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs line-clamp-2 {{ is_null($notification->read_at) ? 'text-gray-800' : 'text-gray-500' }}">{{ $notification->data['message'] }}</p>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                        Tidak ada notifikasi.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown (Top Right) -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 focus:outline-none">
                            <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-48 mt-3 origin-top-right bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-t-lg">Profile Settings</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6 md:p-8">
                <!-- Header Component (Judul Halaman) -->
                @isset($header)
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $header }}</h1>
                    </div>
                @endisset

                <!-- Slot untuk Konten Utama yang Diberikan Oleh Tampilan Anak -->
                {{ $slot }}
            </main>
        </div>

        <!-- Overlay Hitam untuk Sidebar Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 lg:hidden transition-opacity" style="display: none;"></div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#4f46e5',
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#4f46e5',
                    });
                @endif
                
                @if($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        html: '<ul class="text-left list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                        confirmButtonColor: '#4f46e5',
                    });
                @endif
            });
        </script>
    </body>
</html>
