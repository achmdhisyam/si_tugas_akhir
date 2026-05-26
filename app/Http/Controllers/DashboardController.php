<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Menampilkan dashboard sesuai dengan role user yang sedang login   
    public function index()
    {
        $role = Auth::user()->role;

        // Mengarahkan ke view yang berbeda berdasarkan role
        switch ($role) {
            case 'mahasiswa':
                $skripsi = \App\Models\Skripsi::with('pembimbing', 'pembimbing2', 'jadwalSidangs')->where('user_id', \Illuminate\Support\Facades\Auth::id())->latest()->first();
                return view('dashboard.mahasiswa', compact('skripsi'));
            case 'dosen':
                $skripsis = \App\Models\Skripsi::where(function($query) {
                                                    $query->where('dosen_id', \Illuminate\Support\Facades\Auth::id())
                                                          ->orWhere('dosen_id_2', \Illuminate\Support\Facades\Auth::id());
                                               })
                                               ->where('status', 'disetujui')
                                               ->get();
                return view('dashboard.dosen', compact('skripsis'));
            case 'kaprodi':
                $stats = [
                    'total' => \App\Models\Skripsi::count(),
                    'pending' => \App\Models\Skripsi::where('status', 'pending')->count(),
                    'disetujui' => \App\Models\Skripsi::where('status', 'disetujui')->count(),
                    'ditolak' => \App\Models\Skripsi::where('status', 'ditolak')->count(),
                ];

                // Data Grafik
                $chartData = [
                    'sedang_skripsi' => \App\Models\Skripsi::where('status', 'disetujui')->where('progress', '<', 100)->count(),
                    'siap_sidang' => \App\Models\Skripsi::where('status', 'disetujui')->where('progress', 100)->count(),
                    'lulus' => \App\Models\JadwalSidang::where('status', 'selesai')->where('status_kelulusan', 'lulus')->count(),
                ];

                // Mahasiswa terkendala: Skripsi disetujui, progress < 100, tidak ada bimbingan > 30 hari
                $thirtyDaysAgo = \Carbon\Carbon::now()->subDays(30);
                $mahasiswaMacet = \App\Models\Skripsi::with('mahasiswa', 'pembimbing')
                    ->where('status', 'disetujui')
                    ->where('progress', '<', 100)
                    ->whereDoesntHave('bimbingans', function ($query) use ($thirtyDaysAgo) {
                        $query->where('created_at', '>=', $thirtyDaysAgo);
                    })
                    ->get();

                return view('dashboard.kaprodi', compact('stats', 'chartData', 'mahasiswaMacet'));
            case 'admin':
                $users = \App\Models\User::orderBy('created_at', 'desc')->paginate(10);
                return view('dashboard.admin', compact('users'));
            default:
                // Fallback jika tidak ada role yang cocok
                return view('dashboard');
        }
    }

    //Kaprodi mengingatkan dosen pembimbing mahasiswa terkendala
    public function ingatkanDosen(\Illuminate\Http\Request $request, \App\Models\Skripsi $skripsi)
    {
        if (Auth::user()->role !== 'kaprodi') {
            abort(403);
        }

        if ($skripsi->pembimbing) {
            $skripsi->pembimbing->notify(new \App\Notifications\PengingatBimbinganNotification($skripsi));
        }
        
        if ($skripsi->pembimbing2) {
            $skripsi->pembimbing2->notify(new \App\Notifications\PengingatBimbinganNotification($skripsi));
        }

        return back()->with('success', 'Pengingat berhasil dikirim ke Dosen Pembimbing.');
    }
}
