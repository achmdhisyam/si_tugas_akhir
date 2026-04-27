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
        Schema::create('jadwal_sidangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skripsi_id')->constrained('skripsis')->onDelete('cascade');
            $table->dateTime('tanggal');
            $table->enum('jenis', ['Proposal', 'Akhir']);
            $table->decimal('nilai', 5, 2)->nullable();
            $table->foreignId('penguji_id')->nullable()->constrained('users')->onDelete('set null');
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
        Schema::dropIfExists('jadwal_sidangs');
    }
};
