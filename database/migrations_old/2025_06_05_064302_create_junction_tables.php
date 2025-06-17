<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kosan Fasilitas Umum
        Schema::create('kosan_fasilitas_umum', function (Blueprint $table) {
            $table->string('Id_kosan', 20);
            $table->foreignId('Id_fasilitas')->constrained('fasilitas_umum', 'Id_fasilitas');
            $table->decimal('Nilai_x_Bobot', 5, 2);
            $table->timestamps();
            
            $table->primary(['Id_kosan', 'Id_fasilitas']);
        });

        // Kosan Fasilitas Kamar
        Schema::create('kosan_fasilitas_kamar', function (Blueprint $table) {
            $table->string('Id_kosan', 20);
            $table->foreignId('Id_fasilitask')->constrained('fasilitas_kamar', 'Id_fasilitask');
            $table->decimal('Nilai_x_Bobot', 5, 2);
            $table->timestamps();
            
            $table->primary(['Id_kosan', 'Id_fasilitask']);
        });

        // Kosan Fasilitas Kamar Mandi
        Schema::create('kosan_fasilitas_kamar_mandi', function (Blueprint $table) {
            $table->string('Id_kosan', 20);
            $table->foreignId('Id_fasilitask')->constrained('fasilitas_kamar_mandi', 'Id_fasilitask');
            $table->decimal('Nilai_x_Bobot', 5, 2);
            $table->timestamps();
            
            $table->primary(['Id_kosan', 'Id_fasilitask']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kosan_fasilitas_kamar_mandi');
        Schema::dropIfExists('kosan_fasilitas_kamar');
        Schema::dropIfExists('kosan_fasilitas_umum');
    }
};
