<x-app-layout>
    <x-slot name="header">
        Validasi Pengajuan Judul
    </x-slot>

    <div id="antrean" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 scroll-mt-20">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Antrian Pengajuan Judul (Perlu Validasi)</h2>

        @if($pengajuans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider rounded-tl-lg">Mahasiswa</th>
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">File Draft</th>
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider rounded-tr-lg">Aksi Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengajuans as $item)

                        <tr class="hover:bg-gray-50 transition-colors" x-data="{ openForm: false, status: '' }">
                            <td class="py-4 px-4 text-sm text-gray-800 font-medium">
                                {{ $item->mahasiswa->name }}
                                <br><span class="text-xs text-gray-500">{{ $item->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600 w-1/3">
                                {{ $item->judul }}
                            </td>
                            <td class="py-4 px-4 text-sm">
                                @if($item->file_skripsi)
                                    <a href="{{ Storage::url($item->file_skripsi) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 underline">Lihat File</a>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-sm align-top">

                                <button @click="openForm = !openForm" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md font-medium text-xs transition-colors shadow-sm">
                                    <span x-text="openForm ? 'Tutup Form' : 'Verifikasi'"></span>
                                </button>

                                <div x-show="openForm" x-transition class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200" style="display: none;">
                                    <form action="{{ route('skripsi.validasi', $item->id) }}" method="POST" id="form-validasi-{{ $item->id }}" @submit.prevent="
                                        Swal.fire({
                                            title: 'Konfirmasi Keputusan',
                                            text: 'Apakah Anda yakin dengan keputusan ini?',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#4f46e5',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ya, Simpan!',
                                            cancelButtonText: 'Batal'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('form-validasi-{{ $item->id }}').submit();
                                            }
                                        })
                                    ">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Keputusan</label>
                                            <select name="status" x-model="status" required class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="" disabled selected>Pilih</option>
                                                <option value="disetujui">Setujui Judul</option>
                                                <option value="ditolak">Tolak Judul</option>
                                            </select>
                                        </div>

                                        <!-- Dropdown Dosen KHUSUS saat status = disetujui -->
                                        <div x-show="status === 'disetujui'" x-transition class="mb-3 space-y-3" style="display: none;">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Dosen Pembimbing 1</label>
                                                <select name="dosen_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" x-bind:required="status === 'disetujui'">
                                                    <option value="">Pilih Pembimbing 1</option>
                                                    @foreach($dosens as $dosen)
                                                        <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Dosen Pembimbing 2</label>
                                                <select name="dosen_id_2" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" x-bind:required="status === 'disetujui'">
                                                    <option value="">Pilih Pembimbing 2</option>
                                                    @foreach($dosens as $dosen)
                                                        <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- KHUSUS saat status = ditolak -->
                                        <div x-show="status === 'ditolak'" x-transition class="mb-3" style="display: none;">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                                            <textarea name="alasan_penolakan" rows="2" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tuliskan alasan penolakan..." x-bind:required="status === 'ditolak'"></textarea>
                                        </div>

                                        <!-- Submit -->
                                        <button type="submit" class="w-full bg-indigo-600 text-white text-xs font-medium py-2 rounded-md hover:bg-indigo-700 transition-colors mt-1 shadow-sm">
                                            Simpan Keputusan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- State Kosong (Empty State) -->
            <div class="text-center py-8 text-gray-500 text-sm bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tidak ada pengajuan judul yang menunggu validasi saat ini.
            </div>
        @endif
    </div>
</x-app-layout>
