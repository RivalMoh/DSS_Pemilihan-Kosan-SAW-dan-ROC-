<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keamanan table
        Schema::create('keamanan', function (Blueprint $table) {
            $table->id();
            $table->string('tingkat_keamanan');
            $table->integer('nilai');
            $table->timestamps();
        });

        // Kebersihan table
        Schema::create('kebersihan', function (Blueprint $table) {
            $table->id();
            $table->string('tingkat_kebersihan');
            $table->integer('nilai');
            $table->timestamps();
        });

        // Iuran table
        Schema::create('iuran', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->integer('nilai');
            $table->timestamps();
        });

        // Aturan table
        Schema::create('aturan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_aturan');
            $table->integer('nilai');
            $table->timestamps();
        });

        // Ventilasi table
        Schema::create('ventilasi', function (Blueprint $table) {
            $table->id();
            $table->string('kondisi_ventilasi');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventilasi');
        Schema::dropIfExists('aturan');
        Schema::dropIfExists('iuran');
        Schema::dropIfExists('kebersihan');
        Schema::dropIfExists('keamanan');
    }
};
