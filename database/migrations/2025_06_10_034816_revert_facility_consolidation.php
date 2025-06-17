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
        // Recreate the original facility tables without junction tables
        Schema::create('fasilitas_kamar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas');
            $table->integer('bobot');
            $table->timestamps();
        });

        Schema::create('fasilitas_kamar_mandi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas');
            $table->integer('bobot');
            $table->timestamps();
        });

        Schema::create('fasilitas_umum', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas');
            $table->integer('bobot');
            $table->timestamps();
        });

        // Drop the consolidated tables if they exist
        Schema::dropIfExists('kosan_facility');
        Schema::dropIfExists('facilities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration to revert the consolidation
        // To go back, you would need to run the consolidation migrations again
    }
};
