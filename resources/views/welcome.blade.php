<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIATA – Sistem Informasi Administrasi Tugas Akhir</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-100 min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Card Container -->
    <div class="w-full max-w-sm text-center">
        
        <!-- Logo Block – sama dengan sidebar dashboard: indigo-900 bg -->
        <div class="inline-flex items-center gap-3 mb-10">
            <div class="w-10 h-10 bg-indigo-900 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-2xl font-bold tracking-wider text-indigo-900">SIATA</span>
        </div>

        <!-- Headline -->
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Sistem Informasi<br>Administrasi Tugas Akhir</h1>
        <p class="text-sm text-gray-500 mb-10">Platform digital manajemen skripsi dan sidang terpadu.</p>

        <!-- CTA -->
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="block w-full py-3 px-6 rounded-lg bg-indigo-900 hover:bg-indigo-800 text-white text-sm font-semibold shadow-md transition-colors duration-200">
                    Masuk Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="block w-full py-3 px-6 rounded-lg bg-indigo-900 hover:bg-indigo-800 text-white text-sm font-semibold shadow-md transition-colors duration-200">
                    Login Portal Akademik
                </a>
            @endauth
        @endif

        <!-- Feature pills -->
        <div class="flex flex-wrap justify-center gap-2 mt-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium border border-indigo-100">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 inline-block"></span>
                Pengajuan Digital
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium border border-indigo-100">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 inline-block"></span>
                Logbook Bimbingan
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium border border-indigo-100">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 inline-block"></span>
                Jadwal Sidang
            </span>
        </div>
    </div>

    <!-- Footer -->
    <p class="absolute bottom-6 text-xs text-gray-400">© {{ date('Y') }} SIATA – Program Studi</p>

</body>
</html>
