<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kosan_special_feature_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kosan_id')->constrained()->onDelete('cascade');
            $table->foreignId('special_feature_option_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Use a shorter constraint name to avoid MySQL identifier length limitations
            $table->unique(['kosan_id', 'special_feature_option_id'], 'kosan_sf_options_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kosan_special_feature_options');
    }
};
