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
        // Add columns to luas_kamar table
        Schema::table('luas_kamar', function (Blueprint $table) {
            $table->decimal('min_value', 10, 2)->after('range_luas')->nullable();
            $table->decimal('max_value', 10, 2)->after('min_value')->nullable();
        });

        // Add columns to harga_sewa table
        Schema::table('harga_sewa', function (Blueprint $table) {
            $table->decimal('min_value', 15, 2)->after('range_harga')->nullable();
            $table->decimal('max_value', 15, 2)->after('min_value')->nullable();
        });

        // Add columns to estimasi_jarak table
        Schema::table('estimasi_jarak', function (Blueprint $table) {
            $table->decimal('min_value', 10, 2)->after('range_jarak')->nullable();
            $table->decimal('max_value', 10, 2)->after('min_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('luas_kamar', function (Blueprint $table) {
            $table->dropColumn(['min_value', 'max_value']);
        });

        Schema::table('harga_sewa', function (Blueprint $table) {
            $table->dropColumn(['min_value', 'max_value']);
        });

        Schema::table('estimasi_jarak', function (Blueprint $table) {
            $table->dropColumn(['min_value', 'max_value']);
        });
    }
};
