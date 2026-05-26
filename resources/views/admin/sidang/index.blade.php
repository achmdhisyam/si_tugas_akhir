<x-app-layout>
    <x-slot name="header">
        Manajemen Penjadwalan Sidang
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6" x-data="{ openDetail: false, detailData: {} }">
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
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Antrian Sidang Mahasiswa</h3>

            @if($jadwalSidangs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mahasiswa & Skripsi</th>
                                <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Sidang</th>
                                <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status & Waktu</th>
                                <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($jadwalSidangs as $jadwal)
                            <tr class="hover:bg-gray-50 transition-colors" x-data="{ openForm: false }">
                                <td class="py-4 px-4 align-top">
                                    <p class="font-bold text-gray-900 text-sm">{{ $jadwal->skripsi->mahasiswa->name }}</p>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2 w-64">{{ $jadwal->skripsi->judul }}</p>
                                </td>
                                <td class="py-4 px-4 text-sm align-top">
                                    <span class="font-medium text-gray-800">{{ $jadwal->jenis }}</span>
                                </td>
                                <td class="py-4 px-4 text-sm align-top">
                                    @if($jadwal->status === 'menunggu_jadwal')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-amber-100 text-amber-800 border border-amber-200">Menunggu Penjadwalan</span>
                                    @elseif($jadwal->status === 'dijadwalkan')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800 border border-blue-200">Dijadwalkan</span>
                                        <div class="mt-2 text-xs text-gray-600">
                                            <p class="font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y, H:i') }}</p>
                                            <p>Ruang: {{ $jadwal->ruangan }}</p>
                                        </div>
                                    @elseif($jadwal->status === 'selesai')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Selesai</span>
                                        <p class="text-xs mt-1 font-bold text-emerald-600">{{ strtoupper($jadwal->status_kelulusan) }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-top w-64">
                                    @if($jadwal->status === 'menunggu_jadwal' || $jadwal->status === 'dijadwalkan')
                                        <button @click="openForm = !openForm" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md font-medium text-xs transition-colors shadow-sm">
                                            <span x-text="openForm ? 'Tutup Form' : 'Atur Jadwal'"></span>
                                        </button>
                                        
                                        <!-- Form Penjadwalan Inline -->
                                        <div x-show="openForm" x-transition class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-inner" style="display: none;">
                                            <form action="{{ route('admin.sidang.tetapkan', $jadwal->id) }}" method="POST">
                                                @csrf
                                                
                                                <div class="mb-3">
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tanggal & Waktu</label>
                                                    <input type="datetime-local" name="tanggal" required value="{{ $jadwal->tanggal ? date('Y-m-d\TH:i', strtotime($jadwal->tanggal)) : '' }}"
                                                           class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Ruangan Sidang</label>
                                                    <select name="ruangan" required class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">Pilih Ruangan</option>
                                                        @foreach($ruangans as $ruang)
                                                            <option value="{{ $ruang->nama_ruangan }}" {{ $jadwal->ruangan == $ruang->nama_ruangan ? 'selected' : '' }}>
                                                                {{ $ruang->nama_ruangan }} {{ $ruang->kapasitas ? '('.$ruang->kapasitas.' org)' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Dosen Penguji 1</label>
                                                    <select name="penguji_1_id" required class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">Pilih Penguji 1</option>
                                                        @foreach($dosens as $dosen)
                                                            <option value="{{ $dosen->id }}" {{ $jadwal->penguji_1_id == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Dosen Penguji 2</label>
                                                    <select name="penguji_2_id" required class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">Pilih Penguji 2</option>
                                                        @foreach($dosens as $dosen)
                                                            <option value="{{ $dosen->id }}" {{ $jadwal->penguji_2_id == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <button type="submit" class="w-full bg-indigo-600 text-white text-xs font-medium py-1.5 rounded hover:bg-indigo-700 transition-colors shadow-sm">
                                                    Simpan & Kirim Notifikasi
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <button @click="openDetail = true; detailData = {{ json_encode([
                                            'mahasiswa' => $jadwal->skripsi->mahasiswa->name,
                                            'judul' => $jadwal->skripsi->judul,
                                            'nilai' => $jadwal->nilai,
                                            'status' => $jadwal->status_kelulusan,
                                            'penguji1' => $jadwal->penguji1->name ?? '-',
                                            'penguji2' => $jadwal->penguji2->name ?? '-',
                                            'acc_p1' => $jadwal->skripsi->acc_pembimbing_1,
                                            'acc_p2' => $jadwal->skripsi->acc_pembimbing_2,
                                            'acc_u1' => $jadwal->skripsi->acc_penguji_1,
                                            'acc_u2' => $jadwal->skripsi->acc_penguji_2,
                                        ]) }}" class="text-indigo-600 text-xs font-bold hover:underline">
                                            Lihat Detail Nilai
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h4 class="text-gray-800 font-medium mb-1">Antrian Kosong</h4>
                    <p class="text-sm text-gray-500">Belum ada mahasiswa yang mendaftar sidang.</p>
                </div>
            @endif
        </div>

        <!-- MODAL DETAIL NILAI -->
        <div x-show="openDetail" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openDetail" @click="openDetail = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="openDetail" class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-indigo-900 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white">Detail Hasil Sidang</h3>
                        <button @click="openDetail = false" class="text-indigo-200 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Mahasiswa</p>
                            <h4 class="text-lg font-bold text-gray-900" x-text="detailData.mahasiswa"></h4>
                            <p class="text-sm text-gray-600 mt-1 italic" x-text="detailData.judul"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase mb-1">Nilai Akhir</p>
                                <p class="text-3xl font-black text-indigo-900" x-text="detailData.nilai || '-'"></p>
                            </div>
                            <div class="p-4 rounded-xl border" :class="detailData.status === 'lulus' ? 'bg-emerald-50 border-emerald-100' : 'bg-amber-50 border-amber-100'">
                                <p class="text-[10px] font-bold uppercase mb-1" :class="detailData.status === 'lulus' ? 'text-emerald-400' : 'text-amber-400'">Keputusan</p>
                                <p class="text-sm font-bold" :class="detailData.status === 'lulus' ? 'text-emerald-700' : 'text-amber-700'" x-text="detailData.status ? detailData.status.toUpperCase() : '-'"></p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Persetujuan Revisi (ACC)</p>
                            <div class="grid grid-cols-2 gap-2">
                                <template x-if="true">
                                    <div class="flex items-center gap-2 p-2 rounded-lg border border-gray-100 text-xs">
                                        <div class="w-2 h-2 rounded-full" :class="detailData.acc_p1 ? 'bg-emerald-500' : 'bg-gray-300'"></div>
                                        <span class="text-gray-700">Pembimbing 1</span>
                                    </div>
                                </template>
                                <template x-if="true">
                                    <div class="flex items-center gap-2 p-2 rounded-lg border border-gray-100 text-xs">
                                        <div class="w-2 h-2 rounded-full" :class="detailData.acc_p2 ? 'bg-emerald-500' : 'bg-gray-300'"></div>
                                        <span class="text-gray-700">Pembimbing 2</span>
                                    </div>
                                </template>
                                <template x-if="true">
                                    <div class="flex items-center gap-2 p-2 rounded-lg border border-gray-100 text-xs">
                                        <div class="w-2 h-2 rounded-full" :class="detailData.acc_u1 ? 'bg-emerald-500' : 'bg-gray-300'"></div>
                                        <span class="text-gray-700">Penguji 1 (Ketua)</span>
                                    </div>
                                </template>
                                <template x-if="true">
                                    <div class="flex items-center gap-2 p-2 rounded-lg border border-gray-100 text-xs">
                                        <div class="w-2 h-2 rounded-full" :class="detailData.acc_u2 ? 'bg-emerald-500' : 'bg-gray-300'"></div>
                                        <span class="text-gray-700">Penguji 2</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end">
                        <button @click="openDetail = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-50 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
