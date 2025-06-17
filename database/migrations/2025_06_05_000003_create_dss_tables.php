<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Luas Kamar
        Schema::create('luas_kamar', function (Blueprint $table) {
            $table->id();
            $table->string('range_luas');
            $table->integer('nilai');
            $table->timestamps();
        });

        // Harga Sewa
        Schema::create('harga_sewa', function (Blueprint $table) {
            $table->id();
            $table->string('range_harga');
            $table->integer('nilai');
            $table->timestamps();
        });

        // Estimasi Jarak
        Schema::create('estimasi_jarak', function (Blueprint $table) {
            $table->id();
            $table->string('range_jarak');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estimasi_jarak');
        Schema::dropIfExists('harga_sewa');
        Schema::dropIfExists('luas_kamar');
    }
};
