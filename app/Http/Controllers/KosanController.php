<?php

namespace App\Http\Controllers;

use App\Models\Kosan;
use App\Models\SpecialFeatureOption;
use App\Services\SawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KosanController extends Controller
{
    protected $sawService;

    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }

    public function index(Request $request)
    {
        $query = Kosan::query();
        
        // Apply filters if provided
        if ($request->has('min_price')) {
            $query->where('price_per_month', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price_per_month', '<=', $request->max_price);
        }
        
        if ($request->has('min_size')) {
            $query->where('room_size', '>=', $request->min_size);
        }
        
        if ($request->has('max_distance')) {
            $query->where('distance_to_campus', '<=', $request->max_distance);
        }
        
        // Apply special feature filters
        if ($request->has('features')) {
            $featureOptions = $request->features;
            foreach ($featureOptions as $featureId => $optionIds) {
                if (!empty($optionIds)) {
                    $query->whereHas('specialFeatureOptions', function($q) use ($optionIds) {
                        $q->whereIn('special_feature_options.id', $optionIds);
                    });
                }
            }
        }
        
        $kosans = $query->with('specialFeatureOptions')->get();
        
        // If user is authenticated and has preferences, calculate scores
        if (Auth::check() && $userPreference = Auth::user()->preferences()->first()) {
            $results = $this->sawService->calculateScores($kosans, $userPreference);
            return response()->json($results);
        }
        
        // Otherwise, return basic kosan data
        return response()->json($kosans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'distance_to_campus' => 'required|numeric|min:0',
            'price_per_month' => 'required|numeric|min:0',
            'room_size' => 'required|numeric|min:0',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'ventilation_rating' => 'required|integer|min:1|max:5',
            'rules' => 'nullable|string',
            'additional_costs' => 'nullable|numeric|min:0',
            'special_feature_options' => 'nullable|array',
            'special_feature_options.*' => 'exists:special_feature_options,id',
        ]);

        $kosan = Kosan::create($validated);
        
        // Attach special feature options if provided
        if (isset($validated['special_feature_options'])) {
            $kosan->specialFeatureOptions()->sync($validated['special_feature_options']);
        }

        return response()->json($kosan->load('specialFeatureOptions'), 201);
    }

    public function show(Kosan $kosan)
    {
        return response()->json($kosan->load('specialFeatureOptions'));
    }

    public function update(Request $request, Kosan $kosan)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'address' => 'sometimes|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'distance_to_campus' => 'sometimes|numeric|min:0',
            'price_per_month' => 'sometimes|numeric|min:0',
            'room_size' => 'sometimes|numeric|min:0',
            'cleanliness_rating' => 'sometimes|integer|min:1|max:5',
            'ventilation_rating' => 'sometimes|integer|min:1|max:5',
            'rules' => 'nullable|string',
            'additional_costs' => 'nullable|numeric|min:0',
            'special_feature_options' => 'nullable|array',
            'special_feature_options.*' => 'exists:special_feature_options,id',
        ]);

        $kosan->update($validated);
        
        // Sync special feature options if provided
        if (isset($validated['special_feature_options'])) {
            $kosan->specialFeatureOptions()->sync($validated['special_feature_options']);
        }

        return response()->json($kosan->load('specialFeatureOptions'));
    }

    public function destroy(Kosan $kosan)
    {
        $kosan->delete();
        return response()->json(null, 204);
    }
    
    public function getRecommendations(Request $request)
    {
        $user = Auth::user();
        
        if (!$userPreference = $user->preferences()->first()) {
            return response()->json([
                'message' => 'Please set your preferences first'
            ], 400);
        }
        
        $query = Kosan::query();
        
        // Apply filters if provided
        if ($request->has('min_price')) {
            $query->where('price_per_month', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price_per_month', '<=', $request->max_price);
        }
        
        if ($request->has('min_size')) {
            $query->where('room_size', '>=', $request->min_size);
        }
        
        if ($request->has('max_distance')) {
            $query->where('distance_to_campus', '<=', $request->max_distance);
        }
        
        // Apply special feature filters
        if ($request->has('features')) {
            $featureOptions = $request->features;
            foreach ($featureOptions as $featureId => $optionIds) {
                if (!empty($optionIds)) {
                    $query->whereHas('specialFeatureOptions', function($q) use ($optionIds) {
                        $q->whereIn('special_feature_options.id', $optionIds);
                    });
                }
            }
        }
        
        $kosans = $query->with('specialFeatureOptions')->get();
        
        // Calculate scores using SAW and ROC
        $results = $this->sawService->calculateScores($kosans, $userPreference);
        
        return response()->json($results);
    }
}
