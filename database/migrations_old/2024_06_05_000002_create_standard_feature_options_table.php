<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('standard_feature_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_feature_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('min_value', 10, 2)->nullable();
            $table->decimal('max_value', 10, 2)->nullable();
            $table->integer('score'); // 1-10 scale
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('standard_feature_options');
    }
};
