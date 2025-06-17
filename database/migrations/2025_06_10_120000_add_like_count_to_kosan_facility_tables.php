<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add like_count to kosan_fasilitas_kamar
        Schema::table('kosan_fasilitas_kamar', function (Blueprint $table) {
            $table->integer('like_count')->default(0)->after('fasilitas_kamar_id');
            $table->integer('order')->default(0)->after('like_count');
        });

        // Add like_count to kosan_fasilitas_kamar_mandi
        Schema::table('kosan_fasilitas_kamar_mandi', function (Blueprint $table) {
            $table->integer('like_count')->default(0)->after('fasilitas_kamar_mandi_id');
            $table->integer('order')->default(0)->after('like_count');
        });

        // Add like_count to kosan_fasilitas_umum
        Schema::table('kosan_fasilitas_umum', function (Blueprint $table) {
            $table->integer('like_count')->default(0)->after('fasilitas_umum_id');
            $table->integer('order')->default(0)->after('like_count');
        });
    }

    public function down()
    {
        Schema::table('kosan_fasilitas_kamar', function (Blueprint $table) {
            $table->dropColumn('like_count');
            $table->dropColumn('order');
        });

        Schema::table('kosan_fasilitas_kamar_mandi', function (Blueprint $table) {
            $table->dropColumn('like_count');
            $table->dropColumn('order');
        });

        Schema::table('kosan_fasilitas_umum', function (Blueprint $table) {
            $table->dropColumn('like_count');
            $table->dropColumn('order');
        });
    }
};
