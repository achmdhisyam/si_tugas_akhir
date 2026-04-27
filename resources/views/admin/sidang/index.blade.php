<x-app-layout>
    <x-slot name="header">
        Manajemen Penjadwalan Sidang
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
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
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Antrean Sidang Mahasiswa</h3>

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
                                                    <input type="text" name="ruangan" required value="{{ $jadwal->ruangan }}" placeholder="Contoh: R. Rapat 1"
                                                           class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Dosen Penguji 1</label>
                                                    <select name="penguji_1_id" required class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">-- Pilih Penguji 1 --</option>
                                                        @foreach($dosens as $dosen)
                                                            <option value="{{ $dosen->id }}" {{ $jadwal->penguji_1_id == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Dosen Penguji 2</label>
                                                    <select name="penguji_2_id" required class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">-- Pilih Penguji 2 --</option>
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
                                        <a href="#" class="text-indigo-600 text-xs font-medium hover:underline">Lihat Detail Nilai</a>
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
                    <h4 class="text-gray-800 font-medium mb-1">Antrean Kosong</h4>
                    <p class="text-sm text-gray-500">Belum ada mahasiswa yang mendaftar sidang.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
