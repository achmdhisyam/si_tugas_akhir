<x-app-layout>
    <x-slot name="header">
        Jadwal Menguji Sidang
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
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Mahasiswa yang Diuji</h3>

            @if($jadwalSidangs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($jadwalSidangs as $jadwal)
                        <div class="border border-gray-200 rounded-lg p-5 hover:border-indigo-300 transition-colors {{ $jadwal->status === 'selesai' ? 'bg-gray-50' : 'bg-white' }}" x-data="{ openForm: false }">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase {{ $jadwal->jenis === 'Akhir' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800' }}">
                                        Sidang {{ $jadwal->jenis }}
                                    </span>
                                </div>
                                <div>
                                    @if($jadwal->status === 'selesai')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Selesai</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Akan Datang</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-md font-bold text-gray-900">{{ $jadwal->skripsi->mahasiswa->name }}</h4>
                                <p class="text-xs text-gray-500 mb-2">{{ $jadwal->skripsi->mahasiswa->email }}</p>
                                <p class="text-sm font-medium text-gray-800 italic">"{{ $jadwal->skripsi->judul }}"</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-4 text-xs bg-indigo-50 p-3 rounded">
                                <div>
                                    <p class="text-indigo-400 font-semibold mb-0.5">Tanggal</p>
                                    <p class="font-medium text-indigo-900">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-indigo-400 font-semibold mb-0.5">Waktu</p>
                                    <p class="font-medium text-indigo-900">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('H:i') }} WIB</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-indigo-400 font-semibold mb-0.5">Ruangan</p>
                                    <p class="font-medium text-indigo-900">{{ $jadwal->ruangan }}</p>
                                </div>
                            </div>

                            <div class="text-xs text-gray-600 mb-4 space-y-1">
                                <p><span class="font-semibold w-24 inline-block">Pembimbing 1:</span> {{ $jadwal->skripsi->pembimbing ? $jadwal->skripsi->pembimbing->name : '-' }}</p>
                                <p><span class="font-semibold w-24 inline-block">Pembimbing 2:</span> {{ $jadwal->skripsi->pembimbing2 ? $jadwal->skripsi->pembimbing2->name : '-' }}</p>
                                <p><span class="font-semibold w-24 inline-block">Penguji 1:</span> {{ $jadwal->penguji1 ? $jadwal->penguji1->name : '-' }} {{ Auth::id() === $jadwal->penguji_1_id ? '(Anda - Ketua)' : '' }}</p>
                                <p><span class="font-semibold w-24 inline-block">Penguji 2:</span> {{ $jadwal->penguji2 ? $jadwal->penguji2->name : '-' }} {{ Auth::id() === $jadwal->penguji_2_id ? '(Anda)' : '' }}</p>
                            </div>

                            @if($jadwal->skripsi->file_draft_final)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <h5 class="text-xs font-bold text-gray-800 mb-2">Dokumen Sidang</h5>
                                    <a href="{{ Storage::url($jadwal->skripsi->file_draft_final) }}" target="_blank" class="inline-flex items-center text-xs font-medium text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Unduh Draft Final
                                    </a>
                                </div>
                            @endif

                            @if($jadwal->status === 'selesai')
                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium mb-1">Status Kelulusan</p>
                                        @if($jadwal->status_kelulusan === 'lulus')
                                            <p class="text-sm font-bold text-emerald-600">LULUS</p>
                                        @elseif($jadwal->status_kelulusan === 'revisi')
                                            <p class="text-sm font-bold text-amber-600">LULUS (REVISI)</p>
                                        @else
                                            <p class="text-sm font-bold text-red-600">TIDAK LULUS</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 font-medium mb-1">Nilai Akhir</p>
                                        <p class="text-2xl font-bold text-indigo-700">{{ $jadwal->nilai }}</p>
                                    </div>
                                </div>

                                <!-- Validasi Revisi -->
                                @if($jadwal->status_kelulusan === 'revisi' && $jadwal->skripsi->file_revisi)
                                    @php
                                        $isPembimbing1 = Auth::id() === $jadwal->skripsi->dosen_id;
                                        $isPembimbing2 = Auth::id() === $jadwal->skripsi->dosen_id_2;
                                        $isPenguji1 = Auth::id() === $jadwal->penguji_1_id;
                                        $isPenguji2 = Auth::id() === $jadwal->penguji_2_id;

                                        $hasAcc = false;
                                        if ($isPembimbing1 && $jadwal->skripsi->acc_pembimbing_1) $hasAcc = true;
                                        if ($isPembimbing2 && $jadwal->skripsi->acc_pembimbing_2) $hasAcc = true;
                                        if ($isPenguji1 && $jadwal->skripsi->acc_penguji_1) $hasAcc = true;
                                        if ($isPenguji2 && $jadwal->skripsi->acc_penguji_2) $hasAcc = true;
                                    @endphp
                                    <div class="mt-4 p-4 border border-amber-200 bg-amber-50 rounded-lg">
                                        <h5 class="text-xs font-bold text-amber-800 mb-2">Validasi Dokumen Revisi</h5>
                                        <a href="{{ Storage::url($jadwal->skripsi->file_revisi) }}" target="_blank" class="inline-flex items-center text-xs font-medium text-amber-700 bg-amber-100 px-3 py-1.5 rounded hover:bg-amber-200 transition-colors mb-3">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Lihat Dokumen Revisi
                                        </a>

                                        @if(!$hasAcc)
                                            <form id="formAccRevisi-{{ $jadwal->skripsi->id }}" action="{{ route('dosen.sidang.acc_revisi', $jadwal->skripsi->id) }}" method="POST">
                                                @csrf
                                                <button type="button" onclick="confirmAccRevisi({{ $jadwal->skripsi->id }}, '{{ $jadwal->skripsi->mahasiswa->name }}')" class="w-full bg-emerald-600 text-white text-xs font-bold py-2 rounded shadow-sm hover:bg-emerald-700 transition-colors">
                                                    ACC Revisi Mahasiswa
                                                </button>
                                            </form>
                                        @else
                                            <div class="p-2 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded text-xs font-bold text-center">
                                                Anda telah menyetujui revisi ini
                                            </div>
                                        @endif
                                    </div>
                                @endif

                            @elseif(Auth::id() === $jadwal->penguji_1_id)
                                <button @click="openForm = !openForm" class="w-full mt-2 bg-indigo-600 text-white text-xs font-medium py-2 rounded shadow-sm hover:bg-indigo-700 transition-colors">
                                    <span x-text="openForm ? 'Tutup Form Nilai' : 'Input Nilai & Keputusan'"></span>
                                </button>

                                <!-- Form Nilai -->
                                <div x-show="openForm" x-transition class="mt-4 p-4 bg-white border border-indigo-200 rounded-lg shadow-sm" style="display: none;">
                                    <form id="formNilai-{{ $jadwal->id }}" action="{{ route('dosen.sidang.nilai', $jadwal->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Nilai Akhir (0-100)</label>
                                            <input type="number" name="nilai" required min="0" max="100" step="0.01" class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1">Status Kelulusan</label>
                                            <select name="status_kelulusan" required class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">-- Pilih Keputusan --</option>
                                                <option value="lulus">Lulus (Tanpa Revisi)</option>
                                                <option value="revisi">Lulus (Dengan Revisi)</option>
                                                <option value="tidak_lulus">Tidak Lulus (Mengulang)</option>
                                            </select>
                                        </div>

                                        <button type="button" onclick="confirmSimpanNilai({{ $jadwal->id }}, '{{ $jadwal->skripsi->mahasiswa->name }}')" class="w-full bg-emerald-600 text-white text-xs font-medium py-2 rounded hover:bg-emerald-700 transition-colors shadow-sm">
                                            Simpan Nilai
                                        </button>
                                        <p class="text-[10px] text-gray-400 mt-2 text-center">Sebagai Ketua Penguji (Penguji 1), Anda bertanggung jawab memasukkan nilai akhir.</p>
                                    </form>
                                </div>
                            @else
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <p class="text-xs text-amber-600 font-medium text-center">Menunggu Ketua Penguji (Penguji 1) untuk menginput nilai.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h4 class="text-gray-800 font-medium mb-1">Tidak Ada Jadwal</h4>
                    <p class="text-sm text-gray-500">Anda belum memiliki jadwal menguji sidang saat ini.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function confirmSimpanNilai(id, name) {
            Swal.fire({
                title: 'Simpan Nilai?',
                text: "Anda akan menyimpan nilai untuk " + name + ". Data yang sudah disimpan tidak bisa diubah kembali oleh Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formNilai-' + id).submit();
                }
            })
        }

        function confirmAccRevisi(id, name) {
            Swal.fire({
                title: 'Setujui Revisi?',
                text: "Apakah Anda yakin dokumen revisi dari " + name + " sudah sesuai dan layak disetujui?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formAccRevisi-' + id).submit();
                }
            })
        }
    </script>
</x-app-layout>
