<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('skripsis', function (Blueprint $table) {
            $table->string('file_draft_final')->nullable();
            $table->string('file_revisi')->nullable();
            $table->enum('status_revisi', ['belum', 'menunggu', 'selesai'])->default('belum');
            // Status ACC dari setiap dosen (boolean)
            $table->boolean('acc_pembimbing_1')->default(false);
            $table->boolean('acc_pembimbing_2')->default(false);
            $table->boolean('acc_penguji_1')->default(false);
            $table->boolean('acc_penguji_2')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsis', function (Blueprint $table) {
            $table->dropColumn([
                'file_draft_final', 
                'file_revisi', 
                'status_revisi',
                'acc_pembimbing_1',
                'acc_pembimbing_2',
                'acc_penguji_1',
                'acc_penguji_2'
            ]);
        });
    }
};
