<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengajuanSkripsiRequest;
use App\Http\Requests\ValidasiSkripsiRequest;
use App\Models\Skripsi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SkripsiController extends Controller
{
    /**
     * Menampilkan form pengajuan judul skripsi untuk mahasiswa.
     */
    public function create()
    {
        // Cek apakah mahasiswa sudah memiliki pengajuan yang pending atau disetujui
        $skripsiAktif = Skripsi::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'disetujui'])
            ->first();

        if ($skripsiAktif) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah memiliki pengajuan judul yang sedang diproses atau disetujui.');
        }

        return view('mahasiswa.pengajuan');
    }

    /**
     * Menyimpan pengajuan judul skripsi dari mahasiswa.
     */
    public function store(PengajuanSkripsiRequest $request)
    {
        // Pastikan kembali belum ada yang aktif
        $skripsiAktif = Skripsi::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'disetujui'])
            ->first();

        if ($skripsiAktif) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah memiliki pengajuan judul.');
        }

        $validatedData = $request->validated();
        
        // Simpan file
        if ($request->hasFile('file_skripsi')) {
            $path = $request->file('file_skripsi')->store('drafts', 'public');
            $validatedData['file_skripsi'] = $path;
        }

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'pending';
        $validatedData['progress'] = 0;

        $skripsi = Skripsi::create($validatedData);

        // Notifikasi ke Kaprodi
        $kaprodis = User::where('role', 'kaprodi')->get();
        foreach ($kaprodis as $kaprodi) {
            $kaprodi->notify(new \App\Notifications\SkripsiDiajukanNotification($skripsi));
        }

        return redirect()->route('dashboard')->with('success', 'Pengajuan judul skripsi berhasil dikirim dan menunggu validasi.');
    }

    /**
     * Menampilkan halaman daftar validasi untuk Kaprodi.
     */
    public function indexKaprodi()
    {
        $pengajuans = Skripsi::with('mahasiswa')->where('status', 'pending')->get();
        $dosens = User::where('role', 'dosen')->get();
        
        return view('kaprodi.validasi', compact('pengajuans', 'dosens'));
    }

    /**
     * Kaprodi memvalidasi (menyetujui/menolak) pengajuan judul.
     */
    public function validasi(ValidasiSkripsiRequest $request, Skripsi $skripsi)
    {
        $validatedData = $request->validated();

        if ($validatedData['status'] === 'disetujui') {
            $skripsi->update([
                'status' => 'disetujui',
                'dosen_id' => $validatedData['dosen_id'],
                'dosen_id_2' => $validatedData['dosen_id_2'],
                'alasan_penolakan' => null, // Reset alasan penolakan jika sebelumnya ditolak
            ]);
            $message = 'Judul skripsi berhasil disetujui dan pembimbing telah ditetapkan.';
        } else {
            $skripsi->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $validatedData['alasan_penolakan'],
                'dosen_id' => null, // Hapus dosen jika ditolak
                'dosen_id_2' => null, // Hapus dosen 2 jika ditolak
            ]);
            $message = 'Judul skripsi telah ditolak.';
        }

        $skripsi->save();

        // Notifikasi ke Mahasiswa
        $skripsi->mahasiswa->notify(new \App\Notifications\SkripsiDivalidasiNotification($skripsi));

        return back()->with('success', $message);
    }
}
