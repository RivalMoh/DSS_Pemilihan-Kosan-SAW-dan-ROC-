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
use Illuminate\Support\Facades\Cache;
use App\Models\WeightSetting;
use App\Models\AttributeRange;

class KosanRecommendationService
{
    // Default criteria weights (used as fallback)
    protected array $defaultWeights = [
        'harga' => 0.23,              // Reduced from 0.25
        'luas_kamar' => 0.18,         // Reduced from 0.20
        'jarak_kampus' => 0.18,       // Reduced from 0.20
        'keamanan' => 0.10,
        'kebersihan' => 0.08,
        'iuran' => 0.07,
        'aturan' => 0.05,
        'ventilasi' => 0.05,
        'fasilitas_kamar' => 0.05,
        'fasilitas_kamar_mandi' => 0.03,
        'fasilitas_umum' => 0.02,
        'akses_lokasi_pendukung' => 0.06,
    ];


    // Default criteria types (used as fallback)
    protected array $defaultCriteriaTypes = [
        'harga' => 'cost',
        'luas_kamar' => 'benefit',
        'jarak_kampus' => 'cost',
        'keamanan' => 'benefit',
        'kebersihan' => 'benefit',
        'iuran' => 'cost',
        'aturan' => 'benefit',
        'ventilasi' => 'benefit',
        'fasilitas_kamar' => 'benefit',
        'fasilitas_kamar_mandi' => 'benefit',
        'fasilitas_umum' => 'benefit',
        'akses_lokasi_pendukung' => 'benefit',
    ];

    // Cache keys
    protected const CACHE_WEIGHTS_KEY = 'recommendation_weights';
    protected const CACHE_CRITERIA_TYPES_KEY = 'recommendation_criteria_types';
    protected const CACHE_TTL = 3600; // 1 hour

    /**
     * Get all weights from the database or cache
     */
    protected function getWeights(): array
    {
        return Cache::remember(self::CACHE_WEIGHTS_KEY, self::CACHE_TTL, function () {
            try {
                return WeightSetting::active()
                    ->pluck('weight', 'criteria_name')
                    ->toArray();
            } catch (\Exception $e) {
                \Log::error('Error loading weights from database: ' . $e->getMessage());
                return $this->defaultWeights;
            }
        });
    }

    /**
     * Get all criteria types from the database or cache
     */
    protected function getCriteriaTypes(): array
    {
        return Cache::remember(self::CACHE_CRITERIA_TYPES_KEY, self::CACHE_TTL, function () {
            try {
                return WeightSetting::active()
                    ->pluck('criteria_type', 'criteria_name')
                    ->toArray();
            } catch (\Exception $e) {
                \Log::error('Error loading criteria types from database: ' . $e->getMessage());
                return $this->defaultCriteriaTypes;
            }
        });
    }

    /**
     * Clear the recommendation cache
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_WEIGHTS_KEY);
        Cache::forget(self::CACHE_CRITERIA_TYPES_KEY);
    }

    public function calculateRecommendations(array $userPreferences, int $limit = 10): Collection
    {
        // Load weights and criteria types
        $weights = $this->getWeights();
        $criteriaTypes = $this->getCriteriaTypes();

        // If no weights found in database, use defaults
        if (empty($weights)) {
            $weights = $this->defaultWeights;
        }

        // If no criteria types found in database, use defaults
        if (empty($criteriaTypes)) {
            $criteriaTypes = $this->defaultCriteriaTypes;
        }

        // Get all kosan with their relationships
        $kosans = Kosan::with([
            'fasilitas_kamar',
            'fasilitas_kamar_mandi',
            'fasilitas_umum',
            'keamanan',
            'kebersihan',
            'iuran',
            'aturan',
            'ventilasi',
            'akses_lokasi_pendukung'
        ])->get();

        if ($kosans->isEmpty()) {
            return collect();
        }

        // First pass: calculate all scores
        $kosansWithScores = $kosans->map(function ($kosan) use ($weights, $criteriaTypes, $userPreferences) {
            // Calculate scores for each criterion that has a weight
            $scores = [];
            $totalScore = 0;

            // Only calculate scores for criteria that have weights
            foreach ($weights as $criteria => $weight) {
                $method = 'calculate' . str_replace('_', '', ucwords($criteria, '_')) . 'Score';
                if (method_exists($this, $method)) {
                    $scores[$criteria] = $this->$method($kosan, $userPreferences);
                    $totalScore += $scores[$criteria] * $weight;
                } else {
                    // Default score if calculation method doesn't exist
                    $scores[$criteria] = 0;
                }
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

    protected function calculateFacilityScores(Kosan $kosan): array
    {
        $scores = [
            'fasilitas_kamar' => 0,
            'fasilitas_kamar_mandi' => 0,
            'fasilitas_umum' => 0,
            'akses_lokasi_pendukung' => 0,
        ];

        // Calculate facility scores using SMART (Simple Multi-Attribute Rating Technique)
        // For each facility, we'll use the order as priority and calculate a weighted sum
        
        // Kamar facilities
        $totalWeight = $kosan->fasilitas_kamar->sum('bobot');
        if ($totalWeight > 0) {
            $scores['fasilitas_kamar'] = $kosan->fasilitas_kamar->sum(function ($facility) use ($totalWeight) {
                $priority = ($facility->pivot->order ?? 1) / $totalWeight;
                return $facility->bobot * $priority;
            });
        }

        // Kamar mandi facilities
        $totalWeight = $kosan->fasilitas_kamar_mandi->sum('bobot');
        if ($totalWeight > 0) {
            $scores['fasilitas_kamar_mandi'] = $kosan->fasilitas_kamar_mandi->sum(function ($facility) use ($totalWeight) {
                $priority = ($facility->pivot->order ?? 1) / $totalWeight;
                return $facility->bobot * $priority;
            });
        }

        // Umum facilities
        $totalWeight = $kosan->fasilitas_umum->sum('bobot');
        if ($totalWeight > 0) {
            $scores['fasilitas_umum'] = $kosan->fasilitas_umum->sum(function ($facility) use ($totalWeight) {
                $priority = ($facility->pivot->order ?? 1) / $totalWeight;
                return $facility->bobot * $priority;
            });
        }

        // Akses Lokasi Pendukung
        $totalAksesWeight = $kosan->akses_lokasi_pendukung->sum('bobot');
        if ($totalAksesWeight > 0) {
            $scores['akses_lokasi_pendukung'] = $kosan->akses_lokasi_pendukung->sum(function ($akses) use ($totalAksesWeight) {
                $priority = ($akses->pivot->order ?? 1) / $totalAksesWeight;
                // Use count from pivot if available, otherwise default to 1
                $count = $akses->pivot->count ?? 1;
                return $akses->bobot * $priority * $count;
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
