<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kosans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('distance_to_campus', 10, 2)->comment('in kilometers');
            $table->decimal('price_per_month', 12, 2);
            $table->decimal('room_size', 10, 2)->comment('in square meters');
            $table->integer('cleanliness_rating')->default(5);
            $table->integer('ventilation_rating')->default(5);
            $table->text('rules')->nullable();
            $table->decimal('additional_costs', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kosans');
    }
};
