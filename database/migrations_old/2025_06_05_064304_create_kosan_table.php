<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kosan', function (Blueprint $table) {
            $table->string('UniqueID', 20)->primary();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->decimal('harga', 10, 2);
            $table->decimal('luas_kamar', 5, 2);
            $table->decimal('Jarak_kampus', 5, 2);
            
            // Foreign keys
            $table->foreignId('Keamanan')->constrained('keamanan', 'Id_keamanan');
            $table->foreignId('Kebersihan')->constrained('kebersihan', 'Id_kebersihan');
            $table->foreignId('Id_aturan')->constrained('aturan', 'Id_aturan');
            $table->foreignId('Id_iuran')->constrained('iuran', 'Id_iuran');
            $table->foreignId('Id_ventilasi')->constrained('ventilasi', 'Id_ventilasi');
            
            // Additional fields
            $table->text('deskripsi')->nullable();
            $table->string('foto_utama')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kosan');
    }
};
