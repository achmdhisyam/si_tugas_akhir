<x-app-layout>
    <x-slot name="header">
        Daftar Mahasiswa Bimbingan
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Daftar Mahasiswa Bimbingan</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar mahasiswa yang judul skripsinya telah disetujui dan berada di bawah bimbingan Anda</p>
            </div>
            <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-lg">
                <span class="text-sm font-medium text-indigo-800">Total: {{ $skripsis->count() }} Mahasiswa</span>
            </div>
        </div>

        @if($skripsis->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($skripsis as $skripsi)
                    <div class="border border-gray-200 rounded-xl hover:shadow-md transition-shadow bg-white flex flex-col h-full overflow-hidden relative">
                        
                        <!--Notifikasi mahasiswa terkendala -->
                        @if($skripsi->pending_count > 0)
                            <div class="absolute top-0 right-0 mt-3 mr-3 flex h-5 w-5 items-center justify-center">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                            </div>
                        @endif

                        <div class="p-5 flex-grow">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                                    {{ substr($skripsi->mahasiswa->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $skripsi->mahasiswa->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $skripsi->mahasiswa->email }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Judul Skripsi</p>
                                <p class="text-sm text-gray-800 font-medium line-clamp-2" title="{{ $skripsi->judul }}">{{ $skripsi->judul }}</p>
                            </div>

                            <!-- Progress Bar -->
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-medium text-gray-500">Progress</span>
                                    <span class="text-xs font-bold text-emerald-600">{{ $skripsi->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $skripsi->progress }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between mt-auto">
                            <span class="text-xs text-gray-500 font-medium flex items-center">
                                @if($skripsi->pending_count > 0)
                                    <span class="text-amber-600 font-bold">{{ $skripsi->pending_count }} Review Tertunda</span>
                                @else
                                    <span class="text-gray-400">Semua sudah direview</span>
                                @endif
                            </span>
                            
                            <a href="{{ route('dosen.bimbingan.show', $skripsi->id) }}" class="inline-flex items-center px-3 py-1.5 border border-indigo-200 text-xs font-medium rounded text-indigo-700 bg-white hover:bg-indigo-50 transition-colors">
                                Lihat Logbook &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="text-base font-medium text-gray-900 mb-1">Belum Ada Mahasiswa Bimbingan</h3>
                <p class="text-sm text-gray-500">Saat ini Anda belum ditugaskan sebagai dosen pembimbing untuk mahasiswa manapun.</p>
            </div>
        @endif
    </div>
</x-app-layout>
