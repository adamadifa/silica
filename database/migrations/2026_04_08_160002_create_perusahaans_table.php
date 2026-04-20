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
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->enum('status_pengerjaan', ['draft', 'Dikerjakan', 'Dikumpulkan', 'Dinilai'])->default('draft');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
