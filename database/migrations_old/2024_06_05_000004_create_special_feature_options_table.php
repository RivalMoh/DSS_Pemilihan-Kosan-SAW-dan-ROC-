<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('special_feature_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('special_feature_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('roc_weight', 10, 4)->default(0); // Will be calculated based on ROC
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('special_feature_options');
    }
};
