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
        Schema::table('jadwal_sidangs', function (Blueprint $table) {
            $table->renameColumn('penguji_id', 'penguji_1_id');
            $table->foreignId('penguji_2_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ruangan')->nullable();
            $table->enum('status', ['menunggu_jadwal', 'dijadwalkan', 'selesai'])->default('menunggu_jadwal');
            $table->enum('status_kelulusan', ['lulus', 'tidak_lulus', 'revisi'])->nullable();
        });
        
        // Sometimes renameColumn on foreign keys in MySQL requires specific handling. Let's see if this works.
        // If not, we might need to drop the foreign key, rename the column, and recreate the foreign key.
        // Alternatively, we just drop penguji_id and add penguji_1_id and penguji_2_id
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_sidangs', function (Blueprint $table) {
            $table->renameColumn('penguji_1_id', 'penguji_id');
            $table->dropForeign(['penguji_2_id']);
            $table->dropColumn('penguji_2_id');
            $table->dropColumn('ruangan');
            $table->dropColumn('status');
            $table->dropColumn('status_kelulusan');
        });
    }
};
