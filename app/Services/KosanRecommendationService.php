<?php

namespace App\Services;

use App\Models\Kosan;
use App\Models\FasilitasKamar;
use App\Models\FasilitasKamarMandi;
use App\Models\FasilitasUmum;
use App\Models\HargaSewa;
use App\Models\LuasKamar;
use App\Models\EstimasiJarak;
use App\Models\Keamanan;
use App\Models\Kebersihan;
use App\Models\Iuran;
use App\Models\Aturan;
use App\Models\Ventilasi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KosanRecommendationService
{
    // Criteria weights (can be adjusted)
    protected array $weights = [
        'harga' => 0.22,              // Reduced from 0.25
        'luas_kamar' => 0.10,         // Reduced from 0.20
        'jarak_kampus' => 0.17,       // Reduced from 0.20
        'keamanan' => 0.10,
        'kebersihan' => 0.08,
        'iuran' => 0.04,
        'aturan' => 0.05,
        'ventilasi' => 0.05,
        'fasilitas_kamar' => 0.05,
        'fasilitas_kamar_mandi' => 0.03,
        'fasilitas_umum' => 0.02,
        'akses_lokasi_pendukung' => 0.07,  // New weight
    ];

    // Criteria types (benefit or cost)
    protected array $criteriaTypes = [
        'harga' => 'cost',          // Lower is better
        'luas_kamar' => 'benefit',   // Higher is better
        'jarak_kampus' => 'cost',    // Lower is better
        'keamanan' => 'benefit',     // Higher is better
        'kebersihan' => 'benefit',   // Higher is better
        'iuran' => 'cost',          // Lower is better
        'aturan' => 'cost',       // Lower is better
        'ventilasi' => 'benefit',    // Higher is better
        'fasilitas_kamar' => 'benefit',       // Higher is better
        'fasilitas_kamar_mandi' => 'benefit', // Higher is better
        'fasilitas_umum' => 'benefit',        // Higher is better
        'akses_lokasi_pendukung' => 'benefit', // Higher is better
    ];

    /**
     * Clear the recommendation cache
     * 
     * @return void
     */
    public function clearCache(): void
    {
        // Clear any cached recommendations if you're using Laravel's cache
        if (function_exists('cache')) {
            cache()->forget('kosan_recommendations');
        }
        
        // If you're using any other caching mechanism, clear it here
        // For example, if you're using Redis directly:
        // \Illuminate\Support\Facades\Redis::del('kosan_recommendations');
    }

    public function calculateRecommendations(): Collection
    {
        // Get all kosan with their relationships
        $kosans = Kosan::with([
            'fasilitas_kamar',
            'fasilitas_kamar_mandi',
            'fasilitas_umum',
            'keamanan',
            'kebersihan',
            'iuran',
            'aturan',
            'ventilasi'
        ])->get();

        if ($kosans->isEmpty()) {
            return collect();
        }

        // First pass: calculate all scores
        $kosansWithScores = $kosans->map(function ($kosan) {
            // Calculate facility scores using SMART
            $facilityScores = $this->calculateFacilityScores($kosan);
            
            // Calculate attribute scores
            $attributeScores = $this->calculateAttributeScores($kosan);
            
            // Combine all scores
            $totalScore = 0;
            $scores = [];
            
            foreach (array_keys($this->weights) as $criterion) {
                $score = $facilityScores[$criterion] ?? $attributeScores[$criterion] ?? 0;
                $scores[$criterion] = $score;
                $totalScore += $score * $this->weights[$criterion];
            }
            
            return [
                'kosan' => $kosan,
                'scores' => $scores,
                'total_score' => $totalScore
            ];
        });

        // Get min and max scores for normalization
        $minScore = $kosansWithScores->min('total_score');
        $maxScore = $kosansWithScores->max('total_score');

        // Second pass: normalize scores to 0-1 range
        $normalizedKosans = $kosansWithScores->map(function ($kosanData) use ($minScore, $maxScore) {
            // Default to 10% minimum score
            $normalizedScore = 0.1;
            
            if ($maxScore > $minScore) {
                // Scale the remaining 90% based on position in range
                $normalizedScore = 0.1 + 0.9 * (($kosanData['total_score'] - $minScore) / ($maxScore - $minScore));
            } else if ($maxScore > 0) {
                // If all scores are the same but non-zero, set to 100%
                $normalizedScore = 1;
            }
            
            return array_merge($kosanData, [
                'normalized_score' => $normalizedScore,
                'final_percentage' => round($normalizedScore * 100, 1)
            ]);
        });

        // Sort by normalized score in descending order
        $sorted = $normalizedKosans->sortByDesc('normalized_score')->values();
        
        // Debug log first few items
        \Log::info('Top Recommendations', $sorted->take(3)->map(function($item) {
            return [
                'kosan_id' => $item['kosan']->UniqueID,
                'normalized_score' => $item['normalized_score'],
                'total_score' => $item['total_score'],
                'final_percentage' => round($item['normalized_score'] * 100, 2)
            ];
        })->toArray());
        
        return $sorted;
    }

    /**
     * Calculate the SMART weight for a facility based on its order
     * 
     * @param int $order The facility's order (1-based)
     * @param int $totalFacilities Total number of facilities of this type
     * @return float The calculated weight
     */
    protected function calculateSmartWeight(int $order, int $totalFacilities): float
    {
        if ($totalFacilities === 0) {
            return 0;
        }
        
        // Calculate the sum of 1/i from i=order to i=totalFacilities
        $sum = 0;
        for ($i = $order; $i <= $totalFacilities; $i++) {
            $sum += 1 / $i;
        }
        
        // Calculate the normalization factor (sum of 1/i for i=1 to i=totalFacilities)
        $normalizationFactor = 0;
        for ($i = 1; $i <= $totalFacilities; $i++) {
            $normalizationFactor += 1 / $i;
        }
        
        // Avoid division by zero
        if ($normalizationFactor == 0) {
            return 0;
        }
        
        return $sum / $normalizationFactor;
    }

    protected function calculateFacilityScores(Kosan $kosan): array
    {
        $scores = [
            'fasilitas_kamar' => 0,
            'fasilitas_kamar_mandi' => 0,
            'fasilitas_umum' => 0,
            'akses_lokasi_pendukung' => 0,
        ];

        // Calculate facility scores using SMART (Simple Multi-Attribute Rating Technique)
        
        // Kamar facilities
        $facilities = $kosan->fasilitas_kamar;
        if ($facilities->isNotEmpty()) {
            $maxOrder = $facilities->max(function ($facility) {
                return $facility->pivot->order ?? 1;
            });
            $scores['fasilitas_kamar'] = $facilities->sum(function ($facility) use ($maxOrder) {
                $order = $facility->pivot->order ?? 1;
                $weight = $this->calculateSmartWeight($order, $maxOrder);
                return $facility->bobot * $weight;
            });
        }

        // Kamar mandi facilities
        $facilities = $kosan->fasilitas_kamar_mandi;
        if ($facilities->isNotEmpty()) {
            $maxOrder = $facilities->max(function ($facility) {
                return $facility->pivot->order ?? 1;
            });
            $scores['fasilitas_kamar_mandi'] = $facilities->sum(function ($facility) use ($maxOrder) {
                $order = $facility->pivot->order ?? 1;
                $weight = $this->calculateSmartWeight($order, $maxOrder);
                return $facility->bobot * $weight;
            });
        }

        // Umum facilities
        $facilities = $kosan->fasilitas_umum;
        if ($facilities->isNotEmpty()) {
            $maxOrder = $facilities->max(function ($facility) {
                return $facility->pivot->order ?? 1;
            });
            $scores['fasilitas_umum'] = $facilities->sum(function ($facility) use ($maxOrder) {
                $order = $facility->pivot->order ?? 1;
                $weight = $this->calculateSmartWeight($order, $maxOrder);
                return $facility->bobot * $weight;
            });
        }

        // Akses Lokasi Pendukung
        $facilities = $kosan->akses_lokasi_pendukung;
        if ($facilities->isNotEmpty()) {
            $maxOrder = $facilities->max(function ($facility) {
                return $facility->pivot->order ?? 1;
            });
            $scores['akses_lokasi_pendukung'] = $facilities->sum(function ($facility) use ($maxOrder) {
                $order = $facility->pivot->order ?? 1;
                $weight = $this->calculateSmartWeight($order, $maxOrder);
                $count = $facility->pivot->count ?? 1;
                return $facility->bobot * $weight * $count;
            });
        }

        return $scores;
    }

    protected function calculateAttributeScores(Kosan $kosan): array
    {
        $scores = [];
        
        // Harga
        $hargaRange = HargaSewa::whereRaw('? BETWEEN min_value AND max_value', [$kosan->harga])
            ->first();
        $scores['harga'] = $hargaRange ? $hargaRange->nilai : 0;
        
        // Luas Kamar
        $luasRange = LuasKamar::whereRaw('? BETWEEN min_value AND max_value', [$kosan->luas_kamar])
            ->first();
        $scores['luas_kamar'] = $luasRange ? $luasRange->nilai : 0;
        
        // Jarak Kampus
        $jarakRange = EstimasiJarak::whereRaw('? BETWEEN min_value AND max_value', [$kosan->Jarak_kampus])
            ->first();
        $scores['jarak_kampus'] = $jarakRange ? $jarakRange->nilai : 0;
        
        // Other attributes
        $scores['keamanan'] = $kosan->keamanan->nilai ?? 0;
        $scores['kebersihan'] = $kosan->kebersihan->nilai ?? 0;
        $scores['iuran'] = $kosan->iuran->nilai ?? 0;
        $scores['aturan'] = $kosan->aturan->nilai ?? 0;
        $scores['ventilasi'] = $kosan->ventilasi->nilai ?? 0;
        
        return $scores;
    }
    
    /**
     * Normalize a value for SAW method
     * 
     * @param float $value
     * @param string $type 'benefit' or 'cost'
     * @param float $minValue
     * @param float $maxValue
     * @return float
     */
    protected function normalizeValue(float $value, string $type, float $minValue, float $maxValue): float
    {
        if ($maxValue == $minValue) {
            return 1; // Avoid division by zero
        }
        
        if ($type === 'benefit') {
            return ($value - $minValue) / ($maxValue - $minValue);
        } else { // cost
            return ($maxValue - $value) / ($maxValue - $minValue);
        }
    }
}
