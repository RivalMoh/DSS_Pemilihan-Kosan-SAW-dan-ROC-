<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Fasilitas Kamar
        Schema::create('fasilitas_kamar', function (Blueprint $table) {
            $table->id('Id_fasilitask');
            $table->text('fasilitas');
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });

        // Fasilitas Kamar Mandi
        Schema::create('fasilitas_kamar_mandi', function (Blueprint $table) {
            $table->id('Id_fasilitask');
            $table->text('fasilitas');
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });

        // Fasilitas Umum
        Schema::create('fasilitas_umum', function (Blueprint $table) {
            $table->id('Id_fasilitas');
            $table->text('Fasilitas');
            $table->decimal('Nilai', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fasilitas_umum');
        Schema::dropIfExists('fasilitas_kamar_mandi');
        Schema::dropIfExists('fasilitas_kamar');
    }
};
