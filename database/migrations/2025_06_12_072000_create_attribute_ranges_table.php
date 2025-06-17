<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attribute_ranges', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_name');
            $table->string('display_name');
            $table->decimal('min_value', 15, 2)->nullable();
            $table->decimal('max_value', 15, 2)->nullable();
            $table->integer('number_of_groups')->default(5);
            $table->json('group_ranges')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('attribute_name');
        });

        // Insert default ranged attributes
        DB::table('attribute_ranges')->insert([
            [
                'attribute_name' => 'estimasi_jarak',
                'display_name' => 'Estimasi Jarak',
                'min_value' => 0,
                'max_value' => 10, // in km
                'number_of_groups' => 5,
                'is_active' => true
            ],
            [
                'attribute_name' => 'harga_sewa',
                'display_name' => 'Harga Sewa',
                'min_value' => 500000,
                'max_value' => 5000000, // in IDR
                'number_of_groups' => 5,
                'is_active' => true
            ],
            [
                'attribute_name' => 'luas_kamar',
                'display_name' => 'Luas Kamar',
                'min_value' => 9, // in m²
                'max_value' => 36, // in m²
                'number_of_groups' => 5,
                'is_active' => true
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_ranges');
    }
};
