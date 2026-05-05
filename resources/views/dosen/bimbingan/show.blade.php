<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('dosen.bimbingan.index') }}" class="p-2 bg-white rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <span>Logbook Bimbingan: {{ $skripsi->mahasiswa->name }}</span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Informasi Mahasiswa -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <div class="text-center mb-6 border-b border-gray-100 pb-6">
                    <div class="h-20 w-20 mx-auto rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-3xl mb-3">
                        {{ substr($skripsi->mahasiswa->name, 0, 1) }}
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $skripsi->mahasiswa->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $skripsi->mahasiswa->email }}</p>
                </div>
                
                <div class="mb-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Judul Skripsi</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $skripsi->judul }}</p>
                </div>

                    <div class="mb-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tingkat Penyelesaian</p>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-bold text-emerald-600">{{ $skripsi->progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $skripsi->progress }}%"></div>
                    </div>
                </div>

                <!-- Form Override Progress -->
                <div class="border-t border-gray-100 pt-5 mt-5">
                    <p class="text-xs font-semibold text-indigo-800 uppercase tracking-wider mb-3">Update Progress Manual</p>
                    <form action="{{ route('dosen.bimbingan.override', $skripsi->id) }}" method="POST" class="flex gap-2" id="override-form">
                        @csrf
                        <input type="number" name="progress" min="0" max="100" value="{{ $skripsi->progress }}" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <button type="button" onclick="
                            Swal.fire({
                                title: 'Update Progress?',
                                text: 'Anda akan mengubah progress mahasiswa ini secara manual.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#4f46e5',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Ubah!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('override-form').submit();
                                }
                            })
                        " class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
                            Simpan
                        </button>
                    </form>
                    <p class="text-[10px] text-gray-400 mt-2 leading-tight">Gunakan fitur ini jika Anda ingin langsung memajukan/memundurkan progress tanpa harus menunggu ACC bimbingan (misal: langsung set 100% jika sudah siap sidang).</p>
                </div>
            </div>
        </div>

        <!-- Daftar Timeline Logbook -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Timeline Progres & Reviu</h3>

                @if($bimbingans->count() > 0)
                    <div class="space-y-6">
                        @foreach($bimbingans as $bimbingan)
                            <div class="border border-gray-200 rounded-lg overflow-hidden bg-white hover:border-indigo-300 transition-colors" x-data="{ openReview: false, status: '' }">
                                <!-- Header Bimbingan -->
                                <div class="bg-gray-50 px-4 py-3 flex justify-between items-center border-b border-gray-200">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ \Carbon\Carbon::parse($bimbingan->tanggal)->translatedFormat('d F Y') }}
                                        </span>
                                    </div>
                                    <div>
                                        @if($bimbingan->status === 'pending')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                Menunggu Reviu
                                            </span>
                                        @elseif($bimbingan->status === 'disetujui')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                Perlu Revisi
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Body Isi Laporan Mahasiswa -->
                                <div class="p-4">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Laporan Mahasiswa:</p>
                                    <p class="text-sm text-gray-800 mb-3">{{ $bimbingan->catatan }}</p>

                                    @if($bimbingan->file_progres)
                                        <a href="{{ Storage::url($bimbingan->file_progres) }}" target="_blank" class="inline-flex items-center text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 px-3 py-1.5 rounded transition-colors mb-2">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download File Lampiran
                                        </a>
                                    @endif
                                </div>

                                <!-- Footer / Action Area -->
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                                    @if($bimbingan->status === 'pending')
                                        <!-- Tombol untuk membuka form reviu -->
                                        <button @click="openReview = !openReview" class="inline-flex items-center px-4 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700 transition-colors shadow-sm">
                                            <span x-text="openReview ? 'Batal Reviu' : 'Berikan Reviu & Feedback'"></span>
                                        </button>

                                        <!-- Form Reviu -->
                                        <div x-show="openReview" x-transition class="mt-4 p-4 bg-white border border-indigo-100 rounded-lg shadow-sm" style="display: none;">
                                            <form action="{{ route('dosen.bimbingan.review', $bimbingan->id) }}" method="POST" id="form-review-{{ $bimbingan->id }}" @submit.prevent="
                                                Swal.fire({
                                                    title: 'Simpan Reviu?',
                                                    text: 'Pastikan feedback sudah lengkap. Jika Anda memilih Setuju, progress mahasiswa akan otomatis bertambah.',
                                                    icon: 'question',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#4f46e5',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Ya, Simpan!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('form-review-{{ $bimbingan->id }}').submit();
                                                    }
                                                })
                                            ">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Keputusan / Status Progres</label>
                                                    <select name="status" x-model="status" required class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="" disabled selected>-- Pilih --</option>
                                                        <option value="disetujui">ACC / Disetujui (Lanjut Bab Berikutnya)</option>
                                                        <option value="direvisi">Ada Revisi (Perbaiki Dahulu)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Catatan / Feedback untuk Mahasiswa</label>
                                                    <textarea name="catatan_dosen" rows="3" required placeholder="Tuliskan arahan, koreksi, atau saran Anda..." class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                                </div>
                                                <button type="submit" class="w-full bg-indigo-600 text-white text-xs font-medium py-2 rounded-md hover:bg-indigo-700 transition-colors mt-1 shadow-sm">
                                                    Simpan Keputusan & Feedback
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <!-- Jika sudah direview, tampilkan hasil reviewnya -->
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Feedback Anda:</p>
                                            <div class="bg-white p-3 rounded border border-gray-200">
                                                <p class="text-sm text-gray-800 italic">"{{ $bimbingan->catatan_dosen }}"</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <h4 class="text-gray-800 font-medium mb-1">Belum Ada Progres</h4>
                        <p class="text-sm text-gray-500">Mahasiswa ini belum mengirimkan catatan bimbingan apapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
