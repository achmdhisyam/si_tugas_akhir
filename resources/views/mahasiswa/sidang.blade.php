<x-app-layout>
    <x-slot name="header">
        Informasi Jadwal Sidang
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-red-50 text-red-800 border border-red-200 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pendaftaran Sidang</h3>

            @if($skripsi && $skripsi->jadwalSidangs->count() > 0)
                <div class="space-y-4">
                    @foreach($skripsi->jadwalSidangs as $jadwal)
                        <div class="border border-gray-200 rounded-lg p-5 hover:border-indigo-300 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-md font-bold text-gray-900">Sidang {{ $jadwal->jenis }}</h4>
                                    <p class="text-xs text-gray-500">Didaftarkan pada: {{ $jadwal->created_at->format('d M Y') }}</p>
                                </div>
                                <div>
                                    @if($jadwal->status === 'menunggu_jadwal')
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Menunggu Penjadwalan Admin</span>
                                    @elseif($jadwal->status === 'dijadwalkan')
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Telah Dijadwalkan</span>
                                    @elseif($jadwal->status === 'selesai')
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Selesai</span>
                                    @endif
                                </div>
                            </div>

                            @if($jadwal->status !== 'menunggu_jadwal')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium mb-1">Tanggal & Waktu Sidang</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y - H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium mb-1">Ruangan</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $jadwal->ruangan ?? '-' }}</p>
                                    </div>
                                    <div class="md:col-span-2 mt-2 pt-2 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 font-medium mb-2">Tim Penguji</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                                            <p><span class="font-medium text-gray-700">Penguji 1:</span> {{ $jadwal->penguji1 ? $jadwal->penguji1->name : '-' }}</p>
                                            <p><span class="font-medium text-gray-700">Penguji 2:</span> {{ $jadwal->penguji2 ? $jadwal->penguji2->name : '-' }}</p>
                                            <p><span class="font-medium text-gray-700">Pembimbing 1:</span> {{ $skripsi->pembimbing ? $skripsi->pembimbing->name : '-' }}</p>
                                            <p><span class="font-medium text-gray-700">Pembimbing 2:</span> {{ $skripsi->pembimbing2 ? $skripsi->pembimbing2->name : '-' }}</p>
                                        </div>
                                    </div>
                                    @if($jadwal->status === 'selesai')
                                        <div class="md:col-span-2 mt-2 pt-2 border-t border-gray-200 flex justify-between items-center">
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium mb-1">Status Kelulusan</p>
                                                @if($jadwal->status_kelulusan === 'lulus')
                                                    <p class="text-sm font-bold text-emerald-600">LULUS</p>
                                                @elseif($jadwal->status_kelulusan === 'revisi')
                                                    <p class="text-sm font-bold text-amber-600">LULUS BERSYARAT (REVISI)</p>
                                                @else
                                                    <p class="text-sm font-bold text-red-600">TIDAK LULUS</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-500 font-medium mb-1">Nilai Akhir</p>
                                                <p class="text-2xl font-bold text-indigo-700">{{ $jadwal->nilai ?? '-' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-gray-600 mt-2">Pendaftaran Anda telah diterima. Silakan menunggu Admin Tata Usaha untuk menetapkan waktu, ruang, dan dosen penguji.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <p class="text-sm text-gray-500">Anda belum pernah mendaftar sidang.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
