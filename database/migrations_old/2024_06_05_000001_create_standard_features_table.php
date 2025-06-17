<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('standard_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('is_cost')->default(false); // true if lower is better (like price)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('standard_features');
    }
};
