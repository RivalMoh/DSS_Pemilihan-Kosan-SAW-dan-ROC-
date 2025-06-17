<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kosan - Fasilitas Kamar
        Schema::create('kosan_fasilitas_kamar', function (Blueprint $table) {
            $table->id();
            $table->string('kosan_id');
            $table->foreignId('fasilitas_kamar_id')->constrained('fasilitas_kamar');
            $table->timestamps();

            $table->foreign('kosan_id')->references('UniqueID')->on('kosan')->onDelete('cascade');
        });

        // Kosan - Fasilitas Kamar Mandi
        Schema::create('kosan_fasilitas_kamar_mandi', function (Blueprint $table) {
            $table->id();
            $table->string('kosan_id');
            $table->foreignId('fasilitas_kamar_mandi_id')->constrained('fasilitas_kamar_mandi');
            $table->timestamps();

            $table->foreign('kosan_id')->references('UniqueID')->on('kosan')->onDelete('cascade');
        });

        // Kosan - Fasilitas Umum
        Schema::create('kosan_fasilitas_umum', function (Blueprint $table) {
            $table->id();
            $table->string('kosan_id');
            $table->foreignId('fasilitas_umum_id')->constrained('fasilitas_umum');
            $table->timestamps();

            $table->foreign('kosan_id')->references('UniqueID')->on('kosan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kosan_fasilitas_umum');
        Schema::dropIfExists('kosan_fasilitas_kamar_mandi');
        Schema::dropIfExists('kosan_fasilitas_kamar');
    }
};
