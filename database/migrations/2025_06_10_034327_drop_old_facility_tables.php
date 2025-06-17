<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old junction tables first (due to foreign key constraints)
        Schema::dropIfExists('kosan_fasilitas_umum');
        Schema::dropIfExists('kosan_fasilitas_kamar_mandi');
        Schema::dropIfExists('kosan_fasilitas_kamar');
        
        // Then drop the facility tables
        Schema::dropIfExists('fasilitas_umum');
        Schema::dropIfExists('fasilitas_kamar_mandi');
        Schema::dropIfExists('fasilitas_kamar');
    }

    /**
     * Reverse the migrations.
     * 
     * Note: This is a destructive migration and cannot be rolled back
     * as we don't have the schema information to recreate the old tables.
     * If you need to rollback, you should restore from a backup.
     */
    public function down(): void
    {
        // This migration is not reversible
        // You should restore from backup if you need to rollback
    }
};
