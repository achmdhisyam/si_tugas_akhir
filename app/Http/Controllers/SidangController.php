<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skripsi;
use App\Models\JadwalSidang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SidangController extends Controller
{
    /**
     * Mahasiswa melihat info jadwal sidang.
     */
    public function indexMahasiswa()
    {
        $skripsi = Skripsi::with('jadwalSidangs.penguji1', 'jadwalSidangs.penguji2')
            ->where('user_id', Auth::id())
            ->first();

        if (!$skripsi) {
            abort(404, 'Skripsi tidak ditemukan.');
        }

        return view('mahasiswa.sidang', compact('skripsi'));
    }

    /**
     * Mahasiswa mendaftar sidang akhir.
     */
    public function daftar(Request $request)
    {
        $request->validate([
            'skripsi_id' => 'required|exists:skripsis,id',
            'file_draft_final' => 'required|mimes:pdf|max:10240', // Maksimal 10MB
        ]);

        $skripsi = Skripsi::findOrFail($request->skripsi_id);

        if ($skripsi->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($skripsi->progress < 100) {
            return back()->with('error', 'Progress bimbingan belum 100%.');
        }

        // Cek apakah sudah mendaftar sidang akhir dan belum selesai
        $existing = JadwalSidang::where('skripsi_id', $skripsi->id)
            ->where('jenis', 'Akhir')
            ->whereIn('status', ['menunggu_jadwal', 'dijadwalkan'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mendaftar sidang.');
        }

        // Simpan File Draft Final
        $path = $request->file('file_draft_final')->store('draft_final', 'public');
        $skripsi->update(['file_draft_final' => $path]);

        $jadwal = JadwalSidang::create([
            'skripsi_id' => $skripsi->id,
            'jenis' => 'Akhir',
            'status' => 'menunggu_jadwal',
            'tanggal' => now(), // Placeholder, akan diubah admin
        ]);

        // Notifikasi ke Admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SidangDidaftarkanNotification($jadwal));
        }

        return redirect()->route('mahasiswa.sidang')->with('success', 'Berhasil mendaftar sidang. Menunggu penjadwalan oleh Admin.');
    }

    /**
     * Admin melihat antrean sidang.
     */
    public function indexAdmin()
    {
        $jadwalSidangs = JadwalSidang::with(['skripsi.mahasiswa', 'skripsi.pembimbing', 'skripsi.pembimbing2', 'penguji1', 'penguji2'])
            ->orderBy('created_at', 'desc')
            ->get();

        $dosens = User::where('role', 'dosen')->get();
        $ruangans = \App\Models\Ruangan::all();

        return view('admin.sidang.index', compact('jadwalSidangs', 'dosens', 'ruangans'));
    }

    /**
     * Admin menjadwalkan sidang.
     */
    public function tetapkanJadwal(Request $request, JadwalSidang $jadwal)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'ruangan' => 'required|string|max:255',
            'penguji_1_id' => 'required|exists:users,id',
            'penguji_2_id' => 'required|exists:users,id|different:penguji_1_id',
        ]);

        $tanggal = \Carbon\Carbon::parse($request->tanggal);

        // 1. Cek Bentrok Ruangan
        $bentrokRuangan = JadwalSidang::where('tanggal', $tanggal)
            ->where('ruangan', $request->ruangan)
            ->where('id', '!=', $jadwal->id)
            ->where('status', 'dijadwalkan')
            ->exists();

        if ($bentrokRuangan) {
            return back()->with('error', 'Gagal: Ruangan sudah terpakai pada tanggal dan jam tersebut.');
        }

        // 2. Cek Bentrok Dosen
        $bentrokDosen = JadwalSidang::where('tanggal', $tanggal)
            ->where('id', '!=', $jadwal->id)
            ->where('status', 'dijadwalkan')
            ->where(function ($query) use ($request) {
                $query->whereIn('penguji_1_id', [$request->penguji_1_id, $request->penguji_2_id])
                      ->orWhereIn('penguji_2_id', [$request->penguji_1_id, $request->penguji_2_id]);
            })->exists();

        if ($bentrokDosen) {
            return back()->with('error', 'Gagal: Salah satu Dosen Penguji sudah memiliki jadwal sidang di waktu yang persis sama.');
        }

        $jadwal->update([
            'tanggal' => $request->tanggal,
            'ruangan' => $request->ruangan,
            'penguji_1_id' => $request->penguji_1_id,
            'penguji_2_id' => $request->penguji_2_id,
            'status' => 'dijadwalkan',
        ]);

        // Kirim Notifikasi ke semua pihak
        $skripsi = $jadwal->skripsi;
        
        $skripsi->mahasiswa->notify(new \App\Notifications\JadwalSidangDitetapkanNotification($jadwal));
        
        if ($skripsi->pembimbing) {
            $skripsi->pembimbing->notify(new \App\Notifications\JadwalSidangDitetapkanNotification($jadwal));
        }
        if ($skripsi->pembimbing2) {
            $skripsi->pembimbing2->notify(new \App\Notifications\JadwalSidangDitetapkanNotification($jadwal));
        }
        
        if ($jadwal->penguji1) {
            $jadwal->penguji1->notify(new \App\Notifications\JadwalSidangDitetapkanNotification($jadwal));
        }
        if ($jadwal->penguji2) {
            $jadwal->penguji2->notify(new \App\Notifications\JadwalSidangDitetapkanNotification($jadwal));
        }

        return back()->with('success', 'Jadwal sidang berhasil ditetapkan dan notifikasi telah dikirim.');
    }

    /**
     * Dosen melihat jadwal menguji.
     */
    public function indexMenguji()
    {
        $userId = Auth::id();
        $jadwalSidangs = JadwalSidang::with(['skripsi.mahasiswa', 'skripsi.pembimbing', 'skripsi.pembimbing2', 'penguji1', 'penguji2'])
            ->where('penguji_1_id', $userId)
            ->orWhere('penguji_2_id', $userId)
            ->orWhereHas('skripsi', function ($query) use ($userId) {
                $query->where('dosen_id', $userId)
                      ->orWhere('dosen_id_2', $userId);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('dosen.sidang.index', compact('jadwalSidangs'));
    }

    /**
     * Dosen Penguji 1 memberikan nilai dan status kelulusan.
     */
    public function inputNilai(Request $request, JadwalSidang $jadwal)
    {
        if ($jadwal->penguji_1_id !== Auth::id()) {
            abort(403, 'Hanya Penguji 1 (Ketua Penguji) yang dapat menginput nilai akhir.');
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'status_kelulusan' => 'required|in:lulus,tidak_lulus,revisi',
        ]);

        $jadwal->update([
            'nilai' => $request->nilai,
            'status_kelulusan' => $request->status_kelulusan,
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Nilai dan status kelulusan berhasil disimpan.');
    }

    /**
     * Mahasiswa mengunggah dokumen revisi.
     */
    public function uploadRevisi(Request $request)
    {
        $request->validate([
            'skripsi_id' => 'required|exists:skripsis,id',
            'file_revisi' => 'required|mimes:pdf|max:10240',
        ]);

        $skripsi = Skripsi::findOrFail($request->skripsi_id);

        if ($skripsi->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $request->file('file_revisi')->store('revisi', 'public');
        
        $skripsi->update([
            'file_revisi' => $path,
            'status_revisi' => 'menunggu',
            // Reset ACC dosen jika upload ulang
            'acc_pembimbing_1' => false,
            'acc_pembimbing_2' => false,
            'acc_penguji_1' => false,
            'acc_penguji_2' => false,
        ]);

        return back()->with('success', 'Dokumen revisi berhasil diunggah. Menunggu persetujuan dosen.');
    }

    /**
     * Dosen memberikan ACC untuk dokumen revisi.
     */
    public function accRevisi(Request $request, Skripsi $skripsi)
    {
        $userId = Auth::id();
        $isUpdated = false;

        if ($skripsi->dosen_id === $userId) {
            $skripsi->acc_pembimbing_1 = true;
            $isUpdated = true;
        }
        if ($skripsi->dosen_id_2 === $userId) {
            $skripsi->acc_pembimbing_2 = true;
            $isUpdated = true;
        }

        $jadwalAkhir = $skripsi->jadwalSidangs()->where('jenis', 'Akhir')->first();
        if ($jadwalAkhir) {
            if ($jadwalAkhir->penguji_1_id === $userId) {
                $skripsi->acc_penguji_1 = true;
                $isUpdated = true;
            }
            if ($jadwalAkhir->penguji_2_id === $userId) {
                $skripsi->acc_penguji_2 = true;
                $isUpdated = true;
            }
        }

        if (!$isUpdated) {
            abort(403);
        }

        // Cek jika semua harus ACC
        $allAcc = true;
        if (!$skripsi->acc_pembimbing_1) $allAcc = false;
        if ($skripsi->dosen_id_2 && !$skripsi->acc_pembimbing_2) $allAcc = false;
        if ($jadwalAkhir && $jadwalAkhir->penguji_1_id && !$skripsi->acc_penguji_1) $allAcc = false;
        if ($jadwalAkhir && $jadwalAkhir->penguji_2_id && !$skripsi->acc_penguji_2) $allAcc = false;

        if ($allAcc) {
            $skripsi->status_revisi = 'selesai';
        }

        $skripsi->save();

        return back()->with('success', 'Revisi berhasil disetujui.');
    }
}
