<x-app-layout>
    <x-slot name="header">
        Dashboard Dosen Pembimbing
    </x-slot>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Anak Wali Bimbingan</p>
                <p class="text-3xl font-bold text-gray-800">{{ $skripsis->count() }} <span class="text-sm font-medium text-gray-500">Mahasiswa</span></p>
            </div>
            <div class="p-4 rounded-full bg-indigo-50 text-indigo-600">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Rata-rata Progress</p>
                @php
                    $avgProgress = $skripsis->count() > 0 ? round($skripsis->avg('progress')) : 0;
                @endphp
                <p class="text-3xl font-bold text-emerald-600">{{ $avgProgress }}%</p>
            </div>
            <div class="p-4 rounded-full bg-emerald-50 text-emerald-600">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Action / Menu Pintasan -->
    <div class="bg-indigo-600 rounded-xl shadow-md text-white p-6 mb-8 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
        <div class="absolute right-0 top-0 opacity-10">
            <svg class="w-32 h-32 -mt-4 -mr-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"></path></svg>
        </div>
        
        <div class="relative z-10 mb-4 md:mb-0">
            <h2 class="text-xl font-bold mb-2">Pantau Progress Anak Wali</h2>
            <p class="text-indigo-100 text-sm max-w-lg">Lihat laporan bimbingan terbaru dari mahasiswa Anda, berikan reviu, dan pantau tingkat penyelesaian skripsi mereka secara real-time.</p>
        </div>
        
        <div class="relative z-10">
            <a href="{{ route('dosen.bimbingan.index') }}" class="inline-flex items-center px-6 py-3 border-2 border-white rounded-lg text-sm font-semibold hover:bg-white hover:text-indigo-700 transition-colors">
                Buka Logbook Bimbingan &rarr;
            </a>
        </div>
    </div>
</x-app-layout>
