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
        Schema::create('kosan_fasilitas_kamar', function (Blueprint $table) {
            $table->string('kosan_id', 20);
            $table->unsignedBigInteger('fasilitas_kamar_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('kosan_id')->references('UniqueID')->on('kosan')->onDelete('cascade');
            $table->foreign('fasilitas_kamar_id')->references('id')->on('fasilitas_kamar')->onDelete('cascade');

            // Composite primary key
            $table->primary(['kosan_id', 'fasilitas_kamar_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosan_fasilitas_kamar');
    }
};
