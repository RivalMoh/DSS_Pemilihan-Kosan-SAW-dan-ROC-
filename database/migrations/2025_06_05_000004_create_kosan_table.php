<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kosan', function (Blueprint $table) {
            $table->string('UniqueID', 20)->primary();
            $table->string('nama');
            $table->text('alamat');
            $table->decimal('harga', 10, 2);
            $table->decimal('luas_kamar', 5, 2);
            $table->decimal('Jarak_kampus', 5, 2);
            $table->foreignId('keamanan_id')->constrained('keamanan');
            $table->foreignId('kebersihan_id')->constrained('kebersihan');
            $table->foreignId('iuran_id')->constrained('iuran');
            $table->foreignId('aturan_id')->constrained('aturan');
            $table->foreignId('ventilasi_id')->constrained('ventilasi');
            $table->text('deskripsi');
            $table->string('foto_utama');
            $table->integer('jumlah_kamar_tersedia');
            $table->enum('tipe_kost', ['Putri', 'Putra', 'Campur'])->default('Campur');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kosan');
    }
};
