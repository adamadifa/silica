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
        Schema::create('coas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
            $table->string('kode_kategori', 50);
            $table->string('kode_akun');
            $table->string('nama_akun');
            $table->string('saldo_normal');
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Setup foreign key manually since it references a non-id column
            $table->foreign('kode_kategori')->references('kode_kategori')->on('kategori_coas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coas');
    }
};
