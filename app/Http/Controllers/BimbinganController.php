<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Skripsi;
use App\Http\Requests\StoreBimbinganRequest;
use App\Http\Requests\ReviewBimbinganRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BimbinganController extends Controller
{
    // BAGIAN MAHASISWA

    // Tampilkan halaman logbook bimbingan mahasiswa.
    public function index()
    {
        $skripsi = Skripsi::with(['bimbingans' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->where('user_id', Auth::id())->latest()->first();

        if (!$skripsi || $skripsi->status !== 'disetujui') {
            return redirect()->route('dashboard')->with('error', 'Anda harus memiliki judul skripsi yang disetujui untuk mengakses logbook bimbingan.');
        }

        return view('mahasiswa.bimbingan', compact('skripsi'));
    }

    // Simpan pengajuan progres bimbingan mahasiswa  
    public function store(StoreBimbinganRequest $request)
    {
        $validated = $request->validated();
        $skripsi = Skripsi::where('user_id', Auth::id())->where('status', 'disetujui')->firstOrFail();

        $path = null;
        if ($request->hasFile('file_progres')) {
            $path = $request->file('file_progres')->store('bimbingans', 'public');
        }

        $bimbingan = Bimbingan::create([
            'skripsi_id' => $skripsi->id,
            'tanggal' => $validated['tanggal'],
            'catatan' => $validated['catatan'],
            'file_progres' => $path,
            'status' => 'pending'
        ]);

        // Notifikasi ke Dosen Pembimbing 1
        if ($skripsi->pembimbing) {
            $skripsi->pembimbing->notify(new \App\Notifications\BimbinganBaruNotification($bimbingan));
        }

        // Notifikasi ke Dosen Pembimbing 2
        if ($skripsi->pembimbing2) {
            $skripsi->pembimbing2->notify(new \App\Notifications\BimbinganBaruNotification($bimbingan));
        }

        return back()->with('success', 'Catatan progres bimbingan berhasil dikirim. Menunggu reviu dosen.');
    }

    // BAGIAN DOSEN

    // Tampilkan daftar mahasiswa bimbingan  
    public function indexDosen()
    {
        $skripsis = Skripsi::with('mahasiswa')
            ->withCount(['bimbingans as pending_count' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->where(function($query) {
                $query->where('dosen_id', Auth::id())
                      ->orWhere('dosen_id_2', Auth::id());
            })
            ->where('status', 'disetujui')
            ->get();

        return view('dosen.bimbingan.index', compact('skripsis'));
    }

    //timeline bimbingan  satu mahasiswa.  
    public function showDosen(Skripsi $skripsi)
    {
        // Pastikan skripsi ini bimbingan milik dosen yang login
        if ($skripsi->dosen_id !== Auth::id() && $skripsi->dosen_id_2 !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        $bimbingans = $skripsi->bimbingans()->orderBy('created_at', 'desc')->get();

        return view('dosen.bimbingan.show', compact('skripsi', 'bimbingans'));
    }

    //Dosen (menyetujui/merevisi) progres bimbingan  
    public function review(ReviewBimbinganRequest $request, Bimbingan $bimbingan)
    {
        // Pastikan dosen pembimbing yang login adalah pembimbing skripsi ini
        if ($bimbingan->skripsi->dosen_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $bimbingan->update([
            'status' => $validated['status'],
            'catatan_dosen' => $validated['catatan_dosen']
        ]);

        // Auto-update Progress Bar di Skripsi jika disetujui
        if ($validated['status'] === 'disetujui') {
            $skripsi = $bimbingan->skripsi;
            $newProgress = $skripsi->progress + 15; 
            if ($newProgress > 100) $newProgress = 100;
            
            $skripsi->update(['progress' => $newProgress]);
        }

        // Notifikasi ke Mahasiswa
        $bimbingan->skripsi->mahasiswa->notify(new \App\Notifications\BimbinganDireviewNotification($bimbingan));

        return back()->with('success', 'Reviu bimbingan berhasil disimpan.');
    }

    //Dosen Pembimbing mengubah progress mahasiswa secara manual.
    public function overrideProgress(\Illuminate\Http\Request $request, Skripsi $skripsi)
    {
        if ($skripsi->dosen_id !== Auth::id() && $skripsi->dosen_id_2 !== Auth::id()) {
            abort(403, 'Anda bukan pembimbing skripsi ini.');
        }

        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $skripsi->update(['progress' => $request->progress]);

        return back()->with('success', 'Progress mahasiswa berhasil diubah secara manual menjadi ' . $request->progress . '%.');
    }
}
