<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SIATA – Login</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }

            /* Animated fluid background – left panel */
            .fluid-bg {
                background: linear-gradient(135deg, #312e81 0%, #4338ca 40%, #6d28d9 70%, #4f46e5 100%);
                position: relative;
                overflow: hidden;
            }
            .blob {
                position: absolute;
                border-radius: 50%;
                filter: blur(0px);
                opacity: 0.25;
                animation: drift 8s ease-in-out infinite alternate;
            }
            .blob-1 { width: 260px; height: 260px; background: #818cf8; top: -60px; left: -60px; animation-duration: 9s; }
            .blob-2 { width: 200px; height: 200px; background: #a78bfa; bottom: 30px; left: 30px; animation-duration: 11s; }
            .blob-3 { width: 140px; height: 140px; background: #c4b5fd; top: 40%; left: 55%; animation-duration: 7s; }
            .blob-wave {
                position: absolute;
                bottom: 0; left: 0; right: 0;
                height: 120px;
                background: rgba(255,255,255,0.08);
                border-radius: 80% 80% 0 0 / 60px;
            }
            @keyframes drift {
                0%   { transform: translate(0, 0) scale(1); }
                50%  { transform: translate(20px, -15px) scale(1.08); }
                100% { transform: translate(-10px, 20px) scale(0.95); }
            }

            /* Input underline style */
            .input-line {
                border: none;
                border-bottom: 2px solid #e5e7eb;
                border-radius: 0;
                padding: 0.5rem 0;
                width: 100%;
                outline: none;
                background: transparent;
                font-size: 0.875rem;
                color: #111827;
                transition: border-color 0.2s;
            }
            .input-line::placeholder { color: #9ca3af; }
            .input-line:focus { border-bottom-color: #4f46e5; }
            .input-line.is-error { border-bottom-color: #ef4444; }
        </style>
    </head>
    <body class="antialiased min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-purple-50 to-indigo-200 p-4">

        <!-- Outer Card -->
        <div class="w-full max-w-4xl min-h-[520px] flex rounded-2xl shadow-2xl overflow-hidden">

            <!-- ===== LEFT: Branding Panel ===== -->
            <div class="fluid-bg flex w-[40%] sm:w-[45%] flex-col items-center justify-center relative p-10 text-center">

                <!-- Decorative blobs -->
                <div class="blob blob-1"></div>
                <div class="blob blob-2"></div>
                <div class="blob blob-3"></div>
                <div class="blob-wave"></div>

                <!-- Content -->
                <div class="relative z-10">
                    <!-- Icon -->
                    <div class="mx-auto w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center mb-6 backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>

                    <h1 class="text-3xl font-extrabold text-white tracking-wide mb-2">SIATA</h1>
                    <p class="text-indigo-200 text-sm font-medium tracking-widest uppercase mb-6">Sistem Informasi Administrasi<br>Tugas Akhir</p>

                    <div class="h-px w-16 bg-white/30 mx-auto mb-6"></div>

                   
                </div>

                <!-- Bottom URL -->
               
            </div>

            <!-- ===== RIGHT: Login Form ===== -->
            <div class="flex-1 bg-white flex flex-col justify-center px-8 sm:px-12 py-10">

                <!-- Greeting -->
                <div class="mb-8">
                    <p class="text-sm text-gray-400 font-medium mb-0.5">Halo! </p>
                    <h2 class="text-2xl font-extrabold text-indigo-700 mb-1">Selamat Datang</h2>
                    <p class="text-gray-500 text-sm">Silakan login ke akun Anda</p>
                </div>

                <!-- Session status -->
                @if(session('status'))
                    <div class="mb-5 p-3 text-sm bg-green-50 text-green-700 border border-green-200 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Errors -->
                @if($errors->any())
                    <div class="mb-5 p-3 text-sm bg-red-50 text-red-700 border border-red-100 rounded-lg">
                        @foreach($errors->all() as $err) <p>{{ $err }}</p> @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alamat Email</label>
                        <input id="email" type="email" name="email"
                            value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="nama@kampus.ac.id"
                            class="input-line {{ $errors->get('email') ? 'is-error' : '' }}">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Password</label>
                        <input id="password" type="password" name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••"
                            class="input-line {{ $errors->get('password') ? 'is-error' : '' }}">
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none text-sm text-gray-500">
                            <input type="checkbox" name="remember" id="remember_me"
                                class="h-4 w-4 border-gray-300 rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            Ingat saya
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline font-medium">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="pt-1">
                        <button type="submit"
                            class="w-full py-3 rounded-lg bg-indigo-900 hover:bg-indigo-800 active:bg-indigo-950 text-white text-sm font-semibold tracking-wide shadow-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-700 focus:ring-offset-2">
                            MASUK
                        </button>
                    </div>
                </form>

            </div>
            <!-- ===== END RIGHT ===== -->

        </div>

    </body>
</html>
