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
                                
                                @if($jadwal->status === 'selesai' && $jadwal->status_kelulusan === 'revisi')
                                    <div class="mt-4 p-4 border border-amber-200 bg-amber-50 rounded-lg">
                                        <h5 class="text-sm font-bold text-amber-800 mb-2">Tindakan Diperlukan: Unggah Dokumen Revisi</h5>
                                        
                                        @if($skripsi->status_revisi === 'belum')
                                            <form action="{{ route('mahasiswa.sidang.upload_revisi') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                                                @csrf
                                                <input type="hidden" name="skripsi_id" value="{{ $skripsi->id }}">
                                                <input type="file" name="file_revisi" accept="application/pdf" required class="block w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200">
                                                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded shadow-sm whitespace-nowrap">
                                                    Kirim Revisi
                                                </button>
                                            </form>
                                        @elseif($skripsi->status_revisi === 'menunggu')
                                            <p class="text-xs text-amber-700 font-medium">Dokumen revisi telah diunggah. Menunggu persetujuan (ACC) dari Pembimbing dan Penguji.</p>
                                            
                                            <!-- Status ACC Dosen -->
                                            <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                                                <div class="p-2 rounded {{ $skripsi->acc_pembimbing_1 ? 'bg-emerald-100 text-emerald-800' : 'bg-white border border-amber-200 text-amber-800' }}">
                                                    <span class="block font-bold">Pembimbing 1</span>
                                                    {{ $skripsi->acc_pembimbing_1 ? 'Disetujui' : 'Menunggu' }}
                                                </div>
                                                <div class="p-2 rounded {{ $skripsi->acc_pembimbing_2 ? 'bg-emerald-100 text-emerald-800' : 'bg-white border border-amber-200 text-amber-800' }}">
                                                    <span class="block font-bold">Pembimbing 2</span>
                                                    {{ $skripsi->acc_pembimbing_2 ? 'Disetujui' : 'Menunggu' }}
                                                </div>
                                                <div class="p-2 rounded {{ $skripsi->acc_penguji_1 ? 'bg-emerald-100 text-emerald-800' : 'bg-white border border-amber-200 text-amber-800' }}">
                                                    <span class="block font-bold">Penguji 1</span>
                                                    {{ $skripsi->acc_penguji_1 ? 'Disetujui' : 'Menunggu' }}
                                                </div>
                                                <div class="p-2 rounded {{ $skripsi->acc_penguji_2 ? 'bg-emerald-100 text-emerald-800' : 'bg-white border border-amber-200 text-amber-800' }}">
                                                    <span class="block font-bold">Penguji 2</span>
                                                    {{ $skripsi->acc_penguji_2 ? 'Disetujui' : 'Menunggu' }}
                                                </div>
                                            </div>
                                        @elseif($skripsi->status_revisi === 'selesai')
                                            <div class="flex items-center text-emerald-700">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <span class="text-sm font-bold">Revisi Selesai dan Telah Disetujui Semua Dosen.</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
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
