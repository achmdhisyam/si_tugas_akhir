<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SkripsiController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rute Mahasiswa Pengajuan Skripsi
    Route::get('/pengajuan-skripsi', [SkripsiController::class, 'create'])->name('skripsi.create');
    Route::post('/pengajuan-skripsi', [SkripsiController::class, 'store'])->name('skripsi.store');

    // Rute Kaprodi Validasi Skripsi
    Route::get('/kaprodi/validasi-judul', [\App\Http\Controllers\SkripsiController::class, 'indexKaprodi'])->name('kaprodi.validasi');
    Route::post('/skripsi/{skripsi}/validasi', [\App\Http\Controllers\SkripsiController::class, 'validasi'])->name('skripsi.validasi');
    Route::post('/dashboard/ingatkan-dosen/{skripsi}', [\App\Http\Controllers\DashboardController::class, 'ingatkanDosen'])->name('kaprodi.ingatkan-dosen');

    // Rute Bimbingan (Mahasiswa)
    Route::get('/bimbingan', [\App\Http\Controllers\BimbinganController::class, 'index'])->name('bimbingan.index');
    Route::post('/bimbingan', [\App\Http\Controllers\BimbinganController::class, 'store'])->name('bimbingan.store');

    // Rute Bimbingan (Dosen)
    Route::get('/dosen/bimbingan', [\App\Http\Controllers\BimbinganController::class, 'indexDosen'])->name('dosen.bimbingan.index');
    Route::get('/dosen/bimbingan/{skripsi}', [\App\Http\Controllers\BimbinganController::class, 'showDosen'])->name('dosen.bimbingan.show');
    Route::post('/dosen/bimbingan/{bimbingan}/review', [\App\Http\Controllers\BimbinganController::class, 'review'])->name('dosen.bimbingan.review');
    Route::post('/dosen/bimbingan/skripsi/{skripsi}/override-progress', [\App\Http\Controllers\BimbinganController::class, 'overrideProgress'])->name('dosen.bimbingan.override');

    // Rute Sidang (Mahasiswa)
    Route::get('/sidang', [\App\Http\Controllers\SidangController::class, 'indexMahasiswa'])->name('mahasiswa.sidang');
    Route::post('/sidang/daftar', [\App\Http\Controllers\SidangController::class, 'daftar'])->name('mahasiswa.sidang.daftar');
    Route::post('/sidang/upload-revisi', [\App\Http\Controllers\SidangController::class, 'uploadRevisi'])->name('mahasiswa.sidang.upload_revisi');

    // Rute Sidang (Admin)
    Route::get('/admin/sidang', [\App\Http\Controllers\SidangController::class, 'indexAdmin'])->name('admin.sidang.index');
    Route::post('/admin/sidang/{jadwal}/tetapkan', [\App\Http\Controllers\SidangController::class, 'tetapkanJadwal'])->name('admin.sidang.tetapkan');

    // Rute Sidang (Dosen)
    Route::get('/dosen/jadwal-menguji', [\App\Http\Controllers\SidangController::class, 'indexMenguji'])->name('dosen.sidang.index');
    Route::post('/dosen/sidang/{jadwal}/nilai', [\App\Http\Controllers\SidangController::class, 'inputNilai'])->name('dosen.sidang.nilai');
    Route::post('/dosen/sidang/skripsi/{skripsi}/acc-revisi', [\App\Http\Controllers\SidangController::class, 'accRevisi'])->name('dosen.sidang.acc_revisi');

    // Rute Admin User Management
    Route::post('/admin/users', [\App\Http\Controllers\AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Rute Admin Ruangan
    Route::get('/admin/ruangan', [\App\Http\Controllers\RuanganController::class, 'index'])->name('admin.ruangan.index');
    Route::post('/admin/ruangan', [\App\Http\Controllers\RuanganController::class, 'store'])->name('admin.ruangan.store');
    Route::put('/admin/ruangan/{ruangan}', [\App\Http\Controllers\RuanganController::class, 'update'])->name('admin.ruangan.update');
    Route::delete('/admin/ruangan/{ruangan}', [\App\Http\Controllers\RuanganController::class, 'destroy'])->name('admin.ruangan.destroy');

    // Rute Notifikasi
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
