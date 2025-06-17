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
        Schema::table('kosan_fasilitas_kamar', function (Blueprint $table) {
            $table->dropColumn('like_count');
        });

        Schema::table('kosan_fasilitas_kamar_mandi', function (Blueprint $table) {
            $table->dropColumn('like_count');
        });

        Schema::table('kosan_fasilitas_umum', function (Blueprint $table) {
            $table->dropColumn('like_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kosan_fasilitas_kamar', function (Blueprint $table) {
            $table->integer('like_count')->default(0)->after('fasilitas_kamar_id');
        });

        Schema::table('kosan_fasilitas_kamar_mandi', function (Blueprint $table) {
            $table->integer('like_count')->default(0)->after('fasilitas_kamar_mandi_id');
        });

        Schema::table('kosan_fasilitas_umum', function (Blueprint $table) {
            $table->integer('like_count')->default(0)->after('fasilitas_umum_id');
        });
    }
};
