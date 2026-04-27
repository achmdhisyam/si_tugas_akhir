<x-app-layout>
    <x-slot name="header">
        Dashboard Ketua Program Studi
    </x-slot>

    <!-- Grid Statistik Dinamis -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Kartu 1: Total Pengajuan -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 rounded-full bg-indigo-50 text-indigo-600 mr-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Pengajuan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
        </div>

        <!-- Kartu 2: Perlu Divalidasi -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 rounded-full bg-amber-50 text-amber-600 mr-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Perlu Divalidasi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <!-- Kartu 3: Disetujui -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 rounded-full bg-emerald-50 text-emerald-600 mr-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Judul Disetujui</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['disetujui'] }}</p>
            </div>
        </div>

        <!-- Kartu 4: Ditolak -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 rounded-full bg-red-50 text-red-600 mr-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Judul Ditolak</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['ditolak'] }}</p>
            </div>
        </div>
    </div>

    <!-- Hapus Tabel Verifikasi -->
</x-app-layout>
