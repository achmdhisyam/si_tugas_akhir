<x-app-layout>
    <x-slot name="header">
        Logbook Bimbingan Skripsi
    </x-slot>

    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Form Tambah Bimbingan -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Form Progres Baru</h3>
                
                <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data" id="form-bimbingan" @submit.prevent="
                    Swal.fire({
                        title: 'Kirim Progres?',
                        text: 'Pastikan file dan catatan sudah benar.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Kirim!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-bimbingan').submit();
                        }
                    })
                ">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bimbingan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Progres <span class="text-red-500">*</span></label>
                        <textarea name="catatan" rows="3" required placeholder="Contoh: Revisi Bab 1 sesuai arahan sebelumnya..."
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                    </div>

                    <div class="mb-6" x-data="{ fileName: null }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload File Progres</label>
                        <div class="mt-1 flex justify-center px-4 py-4 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition-colors" :class="fileName ? 'bg-indigo-50 border-indigo-300' : 'bg-gray-50'">
                            <div class="text-center">
                                <svg x-show="!fileName" class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <svg x-show="fileName" style="display: none;" class="mx-auto h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                
                                <div class="flex text-sm text-gray-600 justify-center mt-2">
                                    <label class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 px-1">
                                        <span x-text="fileName ? 'Ganti File' : 'Pilih File'"></span>
                                        <input name="file_progres" type="file" class="sr-only" accept=".pdf,.doc,.docx" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : null">
                                    </label>
                                </div>
                                <p class="text-xs font-semibold text-indigo-700 mt-1 truncate max-w-[200px]" x-show="fileName" x-text="fileName" style="display: none;"></p>
                                <p class="text-xs text-gray-500 mt-1" x-show="!fileName">PDF/Word up to 10MB</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kirim Progres
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat Bimbingan -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Bimbingan</h3>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                        {{ $skripsi->bimbingans->count() }} Pertemuan
                    </span>
                </div>

                @if($skripsi->bimbingans->count() > 0)
                    <div class="relative border-l border-gray-200 ml-3 space-y-8">
                        @foreach($skripsi->bimbingans as $bimbingan)
                            @php
                                $color = 'gray';
                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                $badge = 'Menunggu Reviu';
                                
                                if($bimbingan->status == 'disetujui') {
                                    $color = 'emerald';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                                    $badge = 'Disetujui';
                                } elseif($bimbingan->status == 'direvisi') {
                                    $color = 'red';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>';
                                    $badge = 'Perlu Revisi';
                                }
                            @endphp

                            <div class="relative pl-8">
 
                                <div class="absolute -left-3.5 top-1 h-7 w-7 rounded-full border-4 border-white bg-{{ $color }}-500 flex items-center justify-center text-white shadow-sm">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        {!! $icon !!}
                                    </svg>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:border-gray-200 transition-colors">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2">
                                        <span class="text-xs font-medium text-gray-500 mb-1 sm:mb-0">
                                            {{ \Carbon\Carbon::parse($bimbingan->tanggal)->translatedFormat('l, d F Y') }}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                            {{ $badge }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-800 font-medium mb-1">Catatan Anda:</p>
                                    <p class="text-sm text-gray-600 mb-3">{{ $bimbingan->catatan }}</p>

                                    @if($bimbingan->file_progres)
                                        <div class="mb-3">
                                            <a href="{{ Storage::url($bimbingan->file_progres) }}" target="_blank" class="inline-flex items-center text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-md transition-colors">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                Lihat File Lampiran
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Feedback Dosen -->
                                    @if($bimbingan->status !== 'pending')
                                        <div class="mt-4 pt-3 border-t border-gray-200">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-2.5">
                                                    <p class="text-xs font-semibold text-gray-900">Feedback Dosen Pembimbing:</p>
                                                    <p class="text-sm text-gray-700 mt-1 italic break-words">
                                                        "{{ $bimbingan->catatan_dosen ?: 'Tidak ada catatan tertulis.' }}"
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h4 class="text-gray-800 font-medium mb-1">Belum Ada Bimbingan</h4>
                        <p class="text-sm text-gray-500">Silakan isi formulir di samping untuk mulai melaporkan progres skripsi Anda kepada dosen pembimbing.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
