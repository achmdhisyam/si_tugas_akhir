<x-app-layout>
    <x-slot name="header">
        Dashboard Ketua Program Studi
    </x-slot>

    @if(session('success'))
        <div class="max-w-7xl mx-auto mb-6">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

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

    <!-- Section: Analisis & Laporan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Kolom Kiri: Grafik Status Skripsi -->
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Status Skripsi</h3>
            <div class="relative h-64 w-full">
                <canvas id="skripsiChart"></canvas>
            </div>
            <div class="mt-4 text-sm text-gray-500 text-center">
                Data real-time seluruh mahasiswa tingkat akhir.
            </div>
        </div>

        <!-- Kolom Kanan: Laporan Mahasiswa Macet -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Mahasiswa Terkendala (Macet)</h3>
                    <p class="text-sm text-gray-500">Progress < 100% dan tidak bimbingan dalam > 30 hari terakhir.</p>
                </div>
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                    {{ count($mahasiswaMacet) }} Terdeteksi
                </span>
            </div>

            @if(count($mahasiswaMacet) > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembimbing 1</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($mahasiswaMacet as $mhs)
                                <tr class="hover:bg-red-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $mhs->mahasiswa->name }}</div>
                                        <div class="text-xs text-gray-500 truncate w-48">{{ $mhs->judul }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $mhs->pembimbing->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $mhs->progress }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('kaprodi.ingatkan-dosen', $mhs->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded transition-colors text-xs font-bold">
                                                Ingatkan Dosen
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 bg-emerald-50 rounded-lg border border-dashed border-emerald-200">
                    <svg class="mx-auto h-12 w-12 text-emerald-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-emerald-800">Semua Terkendali!</h3>
                    <p class="text-xs text-emerald-600">Tidak ada mahasiswa yang terdeteksi macet bimbingan.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('skripsiChart').getContext('2d');
            const data = @json($chartData);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Sedang Skripsi', 'Siap Sidang', 'Sudah Lulus'],
                    datasets: [{
                        data: [data.sedang_skripsi, data.siap_sidang, data.lulus],
                        backgroundColor: [
                            '#818cf8', // Indigo-400
                            '#fbbf24', // Amber-400
                            '#34d399'  // Emerald-400
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</x-app-layout>
