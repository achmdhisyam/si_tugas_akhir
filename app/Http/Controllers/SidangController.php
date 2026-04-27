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

        return view('admin.sidang.index', compact('jadwalSidangs', 'dosens'));
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
        $jadwalSidangs = JadwalSidang::with(['skripsi.mahasiswa', 'skripsi.pembimbing', 'skripsi.pembimbing2', 'penguji1', 'penguji2'])
            ->where('penguji_1_id', Auth::id())
            ->orWhere('penguji_2_id', Auth::id())
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
}
