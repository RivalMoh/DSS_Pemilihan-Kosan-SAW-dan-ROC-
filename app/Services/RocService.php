<?php

namespace App\Services;

use App\Models\SpecialFeature;
use App\Models\SpecialFeatureOption;
use App\Models\UserPreference;

class RocService
{
    public function calculateWeights(UserPreference $userPreference)
    {
        $rankings = $userPreference->special_feature_rankings;
        
        if (empty($rankings)) {
            return [];
        }

        // Sort rankings while preserving keys
        asort($rankings);
        
        $n = count($rankings);
        $weights = [];
        
        // Calculate ROC weights
        foreach ($rankings as $featureId => $rank) {
            $weight = 0;
            for ($i = $rank; $i <= $n; $i++) {
                $weight += 1 / $i;
            }
            $weight /= $n;
            $weights[$featureId] = $weight;
        }
        
        // Update ROC weights in the database
        foreach ($weights as $featureId => $weight) {
            SpecialFeatureOption::where('special_feature_id', $featureId)
                ->update(['roc_weight' => $weight]);
        }
        
        return $weights;
    }
    
    public function getFeatureRankingsWithWeights()
    {
        $features = SpecialFeature::with('options')->get();
        $result = [];
        
        foreach ($features as $feature) {
            $result[] = [
                'id' => $feature->id,
                'name' => $feature->name,
                'code' => $feature->code,
                'options' => $feature->options->map(function($option) {
                    return [
                        'id' => $option->id,
                        'name' => $option->name,
                        'roc_weight' => $option->roc_weight
                    ];
                })
            ];
        }
        
        return $result;
    }
}
