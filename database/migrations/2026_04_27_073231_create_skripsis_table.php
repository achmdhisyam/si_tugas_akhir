<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('skripsis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->string('file_skripsi')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->integer('progress')->default(0);
            $table->foreignId('dosen_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('dosen_id_2')->nullable()->constrained('users')->nullOnDelete();
            
            // Final documents & revisions
            $table->string('file_draft_final')->nullable();
            $table->string('file_revisi')->nullable();
            $table->enum('status_revisi', ['belum', 'menunggu', 'selesai'])->default('belum');
            
            // Approval status from each examiner/advisor
            $table->boolean('acc_pembimbing_1')->default(false);
            $table->boolean('acc_pembimbing_2')->default(false);
            $table->boolean('acc_penguji_1')->default(false);
            $table->boolean('acc_penguji_2')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('skripsis');
    }
};
