<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Keamanan Table
        Schema::create('keamanan', function (Blueprint $table) {
            $table->id('Id_keamanan');
            $table->string('jenis_keamanan', 100);
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });

        // Kebersihan Table
        Schema::create('kebersihan', function (Blueprint $table) {
            $table->id('Id_kebersihan');
            $table->string('jenis_kebersihan', 100);
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });

        // Iuran Table
        Schema::create('iuran', function (Blueprint $table) {
            $table->id('Id_iuran');
            $table->string('iuran', 100);
            $table->decimal('nilai', 10, 2);
            $table->timestamps();
        });

        // Aturan Table
        Schema::create('aturan', function (Blueprint $table) {
            $table->id('Id_aturan');
            $table->text('aturan');
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });

        // Ventilasi Table
        Schema::create('ventilasi', function (Blueprint $table) {
            $table->id('Id_ventilasi');
            $table->string('ventilasi', 100);
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventilasi');
        Schema::dropIfExists('aturan');
        Schema::dropIfExists('iuran');
        Schema::dropIfExists('kebersihan');
        Schema::dropIfExists('keamanan');
    }
};
