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
        Schema::create('kosan_akses_lokasi_pendukung', function (Blueprint $table) {
            $table->id();
            $table->string('kosan_id', 20);
            $table->unsignedBigInteger('akses_lokasi_pendukung_id');
            $table->integer('count')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('kosan_id')->references('UniqueID')->on('kosan')->onDelete('cascade');
            $table->foreign('akses_lokasi_pendukung_id')->references('id')->on('akses_lokasi_pendukung')->onDelete('cascade');

            // Composite unique key
            $table->unique(['kosan_id', 'akses_lokasi_pendukung_id'], 'kosan_akses_lokasi_pendukung_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosan_akses_lokasi_pendukung');
    }
};
