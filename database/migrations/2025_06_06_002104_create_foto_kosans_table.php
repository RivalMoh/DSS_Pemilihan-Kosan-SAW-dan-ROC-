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
        Schema::create('foto_kosans', function (Blueprint $table) {
            $table->id();
            $table->string('kosan_id');
            $table->foreign('kosan_id')->references('UniqueID')->on('kosan')->onDelete('cascade');
            $table->string('path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index('kosan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_kosans');
    }
};
