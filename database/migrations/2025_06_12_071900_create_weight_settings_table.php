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
        Schema::create('weight_settings', function (Blueprint $table) {
            $table->id();
            $table->string('criteria_name')->unique();
            $table->decimal('weight', 5, 4);
            $table->enum('criteria_type', ['benefit', 'cost']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default weights from KosanRecommendationService
        DB::table('weight_settings')->insert([
            ['criteria_name' => 'harga', 'weight' => 0.23, 'criteria_type' => 'cost', 'is_active' => true],
            ['criteria_name' => 'luas_kamar', 'weight' => 0.18, 'criteria_type' => 'benefit', 'is_active' => true],
            ['criteria_name' => 'jarak_kampus', 'weight' => 0.18, 'criteria_type' => 'cost', 'is_active' => true],
            ['criteria_name' => 'keamanan', 'weight' => 0.10, 'criteria_type' => 'benefit', 'is_active' => true],
            ['criteria_name' => 'kebersihan', 'weight' => 0.08, 'criteria_type' => 'benefit', 'is_active' => true],
            ['criteria_name' => 'iuran', 'weight' => 0.07, 'criteria_type' => 'cost', 'is_active' => true],
            ['criteria_name' => 'aturan', 'weight' => 0.05, 'criteria_type' => 'benefit', 'is_active' => true],
            ['criteria_name' => 'ventilasi', 'weight' => 0.05, 'criteria_type' => 'benefit', 'is_active' => true],
            ['criteria_name' => 'fasilitas_kamar', 'weight' => 0.05, 'criteria_type' => 'benefit', 'is_active' => true],
            ['criteria_name' => 'fasilitas_kamar_mandi', 'weight' => 0.03, 'criteria_type' => 'benefit', 'is_active' => true],
            ['criteria_name' => 'fasilitas_umum', 'weight' => 0.02, 'criteria_type' => 'benefit', 'is_active' => true],
            ['criteria_name' => 'akses_lokasi_pendukung', 'weight' => 0.06, 'criteria_type' => 'benefit', 'is_active' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_settings');
    }
};
