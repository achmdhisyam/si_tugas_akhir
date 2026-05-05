<x-app-layout>
    <x-slot name="header">
        Dashboard Mahasiswa
    </x-slot>

    <!-- Layout Grid responsif untuk Kartu Informasi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Kartu Ringkasan Status Skripsi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Status Pengajuan Skripsi</h2>
                <p class="text-gray-500 text-sm">Menampilkan informasi terkini mengenai pengajuan skripsi Anda.</p>
            </div>
            
            @if($skripsi)
                @php
                    $statusColor = 'gray';
                    $statusText = 'Belum Diketahui';
                    if ($skripsi->status == 'pending') {
                        $statusColor = 'amber';
                        $statusText = 'Menunggu Validasi';
                    } elseif ($skripsi->status == 'disetujui') {
                        $statusColor = 'emerald';
                        $statusText = 'Disetujui';
                    } elseif ($skripsi->status == 'ditolak') {
                        $statusColor = 'red';
                        $statusText = 'Ditolak';
                    }
                @endphp
                <div class="mt-6 flex items-start justify-between p-4 bg-{{ $statusColor }}-50 rounded-lg border border-{{ $statusColor }}-100">
                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-600 rounded-full mt-1">
                            @if($skripsi->status == 'disetujui')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @elseif($skripsi->status == 'ditolak')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-{{ $statusColor }}-800">{{ $statusText }}</p>
                            <p class="text-xs text-{{ $statusColor }}-700 mt-1 font-medium">{{ $skripsi->judul }}</p>
                            @if($skripsi->status == 'ditolak' && $skripsi->alasan_penolakan)
                                <div class="mt-2 text-xs text-red-600 bg-red-100 p-2 rounded">
                                    <strong>Alasan:</strong> {{ $skripsi->alasan_penolakan }}
                                </div>
                            @elseif($skripsi->status == 'disetujui' && $skripsi->pembimbing)
                                <div class="mt-2 text-xs text-emerald-700">
                                    <strong>Pembimbing 1:</strong> {{ $skripsi->pembimbing->name }}
                                    @if($skripsi->pembimbing2)
                                        <br><strong>Pembimbing 2:</strong> {{ $skripsi->pembimbing2->name }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-6 flex flex-col items-center justify-center p-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-sm text-gray-500 text-center">Belum ada pengajuan judul.</p>
                    <a href="{{ route('skripsi.create') }}" class="mt-3 text-xs font-medium text-indigo-600 hover:text-indigo-800">Ajukan Sekarang &rarr;</a>
                </div>
            @endif
        </div>

        <!-- Kartu Progress Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Progress Pengerjaan</h2>
                <p class="text-gray-500 text-sm">Pantau sejauh mana penyelesaian skripsi Anda melalui bimbingan terakhir.</p>
            </div>
            
            <div class="mt-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Tingkat Penyelesaian</span>
                    <span class="text-sm font-bold text-emerald-600">{{ $skripsi ? $skripsi->progress : 0 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500" style="width: {{ $skripsi ? $skripsi->progress : 0 }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-3 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Terakhir diperbarui: {{ $skripsi ? $skripsi->updated_at->diffForHumans() : '-' }}
                </p>

                @if($skripsi && $skripsi->progress >= 100)
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        @if($skripsi->jadwalSidangs->count() > 0)
                            <a href="{{ route('mahasiswa.sidang') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Lihat Info Sidang
                            </a>
                        @else
                            <form id="formDaftarSidang" action="{{ route('mahasiswa.sidang.daftar') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input type="hidden" name="skripsi_id" value="{{ $skripsi->id }}">
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Unggah Draft Final (PDF)</label>
                                    <input type="file" name="file_draft_final" id="file_draft_final" accept="application/pdf" required class="block w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>

                                <button type="button" onclick="confirmDaftarSidang()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors animate-pulse hover:animate-none">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Daftar Sidang Akhir
                                </button>
                            </form>

                            <script>
                                function confirmDaftarSidang() {
                                    const fileInput = document.getElementById('file_draft_final');
                                    if (!fileInput.value) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Dokumen Belum Dipilih',
                                            text: 'Silakan pilih file Draft Final Skripsi (PDF) terlebih dahulu.',
                                            confirmButtonColor: '#4f46e5',
                                        });
                                        return;
                                    }

                                    Swal.fire({
                                        title: 'Daftar Sidang Akhir?',
                                        text: "Pastikan dokumen draft final yang Anda unggah sudah benar dan sesuai dengan arahan pembimbing.",
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#059669',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya, Daftar Sekarang',
                                        cancelButtonText: 'Batal',
                                        background: '#ffffff',
                                        borderRadius: '1rem',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('formDaftarSidang').submit();
                                        }
                                    })
                                }
                            </script>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
