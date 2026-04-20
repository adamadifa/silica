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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nomor_bukti');
            $table->text('keterangan')->nullable();
            $table->enum('tipe_jurnal', ['Umum', 'Penyesuaian', 'Penutup'])->default('Umum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
