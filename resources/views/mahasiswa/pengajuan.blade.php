<x-app-layout>
    <x-slot name="header">
        Form Pengajuan Judul Skripsi
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800">Informasi Pengajuan</h2>
                <p class="text-sm text-gray-500 mt-1">Silakan lengkapi form di bawah ini untuk mengajukan judul skripsi baru. Pastikan draft telah sesuai dengan panduan.</p>
            </div>

            <form action="{{ route('skripsi.store') }}" method="POST" enctype="multipart/form-data" id="form-pengajuan" @submit.prevent="
                Swal.fire({
                    title: 'Ajukan Judul?',
                    text: 'Pastikan data dan file draft yang Anda unggah sudah benar.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ajukan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-pengajuan').submit();
                    }
                })
            ">
                @csrf
                
                <div class="mb-6">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Skripsi <span class="text-red-500">*</span></label>
                    <textarea id="judul" name="judul" rows="3" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                              placeholder="Masukkan judul skripsi dengan jelas dan lengkap...">{{ old('judul') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Maksimal 255 karakter.</p>
                </div>

                <div class="mb-8" x-data="{ fileName: null }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Draft Proposal <span class="text-red-500">*</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition-colors" :class="fileName ? 'bg-indigo-50 border-indigo-300' : 'bg-gray-50'">
                        <div class="space-y-1 text-center">
                            <svg x-show="!fileName" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <svg x-show="fileName" style="display: none;" class="mx-auto h-12 w-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file_skripsi" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none px-1">
                                    <span x-text="fileName ? 'Ganti File' : 'Pilih file'"></span>
                                    <input id="file_skripsi" name="file_skripsi" type="file" class="sr-only" accept=".pdf,.doc,.docx" required @change="fileName = $event.target.files[0] ? $event.target.files[0].name : null">
                                </label>
                                <p class="pl-1" x-show="!fileName">atau drag and drop</p>
                            </div>
                            <p class="text-xs font-semibold text-indigo-700 mt-2" x-show="fileName" x-text="fileName" style="display: none;"></p>
                            <p class="text-xs text-gray-500" x-show="!fileName">PDF, DOC, DOCX up to 10MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                        Ajukan Judul
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
