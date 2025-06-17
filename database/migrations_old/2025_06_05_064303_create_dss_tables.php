<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Estimasi Jarak
        Schema::create('estimasi_jarak', function (Blueprint $table) {
            $table->id('Id_jarak');
            $table->string('range_jarak', 50);
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });

        // Harga Sewa
        Schema::create('harga_sewa', function (Blueprint $table) {
            $table->id('Id_harga');
            $table->string('range_harga', 50);
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });

        // Luas Kamar
        Schema::create('luas_kamar', function (Blueprint $table) {
            $table->id('Id_luas');
            $table->string('range_luas_kamar', 50);
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('luas_kamar');
        Schema::dropIfExists('harga_sewa');
        Schema::dropIfExists('estimasi_jarak');
    }
};
