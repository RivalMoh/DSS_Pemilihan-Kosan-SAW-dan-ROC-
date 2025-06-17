<?php

namespace App\Services;

use App\Models\Kosan;
use App\Models\StandardFeature;
use App\Models\UserPreference;
use Illuminate\Support\Collection;

class SawService
{
    public function calculateScores($kosans, $userPreference = null)
    {
        $standardFeatures = StandardFeature::with('options')->get();
        
        // Get weights from user preferences or use equal weights
        $weights = $userPreference?->standard_feature_weights ?? [];
        
        // Normalize weights to sum to 1
        $totalWeight = array_sum($weights);
        $normalizedWeights = [];
        
        foreach ($weights as $featureId => $weight) {
            $normalizedWeights[$featureId] = $totalWeight > 0 ? $weight / $totalWeight : 0;
        }
        
        // Get min and max values for each feature for normalization
        $minMax = $this->getMinMaxValues($kosans, $standardFeatures);
        
        $results = [];
        
        foreach ($kosans as $kosan) {
            $score = 0;
            $featureScores = [];
            
            foreach ($standardFeatures as $feature) {
                $value = $this->getFeatureValue($kosan, $feature);
                $normalizedValue = $this->normalizeValue($value, $feature, $minMax);
                $weight = $normalizedWeights[$feature->id] ?? (1 / count($standardFeatures));
                
                // For cost criteria (like price), lower is better, so we invert the normalized value
                if ($feature->is_cost) {
                    $normalizedValue = 1 - $normalizedValue;
                }
                
                $featureScores[$feature->code] = [
                    'value' => $value,
                    'normalized' => $normalizedValue,
                    'weight' => $weight,
                    'score' => $normalizedValue * $weight
                ];
                
                $score += $normalizedValue * $weight;
            }
            
            // Add ROC score if user preferences are provided
            $rocScore = 0;
            if ($userPreference) {
                $rocScore = $kosan->calculateRocScore($userPreference);
                $score += $rocScore;
            }
            
            $results[] = [
                'kosan' => $kosan,
                'score' => $score,
                'feature_scores' => $featureScores,
                'roc_score' => $rocScore
            ];
        }
        
        // Sort results by score in descending order
        usort($results, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $results;
    }
    
    protected function getFeatureValue(Kosan $kosan, $feature)
    {
        $featureCode = strtolower($feature->code);
        
        // Map feature codes to model attributes
        $attributeMap = [
            'price' => 'price_per_month',
            'distance' => 'distance_to_campus',
            'size' => 'room_size',
            'cleanliness' => 'cleanliness_rating',
            'ventilation' => 'ventilation_rating',
            'additional_costs' => 'additional_costs',
            'rules' => 'rules'
        ];
        
        $attribute = $attributeMap[$featureCode] ?? $featureCode;
        
        // For special handling of rules (text field)
        if ($attribute === 'rules') {
            return $kosan->rules ? 1 : 0; // Simple binary for now
        }
        
        return $kosan->$attribute ?? 0;
    }
    
    protected function normalizeValue($value, $feature, $minMax)
    {
        $min = $minMax[$feature->id]['min'];
        $max = $minMax[$feature->id]['max'];
        
        // Avoid division by zero
        if ($max === $min) {
            return 1;
        }
        
        return ($value - $min) / ($max - $min);
    }
    
    protected function getMinMaxValues($kosans, $features)
    {
        $minMax = [];
        
        foreach ($features as $feature) {
            $values = [];
            
            foreach ($kosans as $kosan) {
                $values[] = $this->getFeatureValue($kosan, $feature);
            }
            
            $minMax[$feature->id] = [
                'min' => min($values),
                'max' => max($values)
            ];
        }
        
        return $minMax;
    }
}
