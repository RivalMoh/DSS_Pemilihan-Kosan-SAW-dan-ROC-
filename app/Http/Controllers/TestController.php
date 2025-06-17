<?php

namespace App\Http\Controllers;

use App\Services\KosanRecommendationService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $recommendationService;

    public function __construct(KosanRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function testRecommendation()
    {
        // Sample user preferences
        $userPreferences = [
            'harga' => 1000000,
            'luas_kamar' => 20,
            'jarak_kampus' => 2.5,
            'keamanan' => ['pagar_tinggi', 'cctv'],
            'kebersihan' => 'bersih',
            'iuran' => ['listrik', 'air'],
            'fasilitas_kamar' => ['ac', 'lemari', 'meja', 'kursi', 'wifi'],
            'fasilitas_kamar_mandi' => ['air_bersih', 'air_panas', 'kloset_duduk'],
            'fasilitas_umum' => ['parkir_motor', 'parkir_mobil', 'dapur', 'ruang_tamu'],
            'akses_lokasi_pendukung' => ['warung_makan', 'minimarket', 'atm', 'halte_bus']
        ];

        // Get recommendations
        $recommendations = $this->recommendationService->calculateRecommendations($userPreferences, 5);

        // Get weights and criteria types for display
        $weights = $this->recommendationService->getWeights();
        $criteriaTypes = $this->recommendationService->getCriteriaTypes();

        return response()->json([
            'weights' => $weights,
            'criteria_types' => $criteriaTypes,
            'recommendations' => $recommendations
        ]);
    }
}
