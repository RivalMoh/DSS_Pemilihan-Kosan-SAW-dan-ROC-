<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use App\Services\KosanRecommendationService;

class WeightSetting extends Model
{
    protected $table = 'weight_settings';
    
    protected $fillable = [
        'criteria_name',
        'weight',
        'criteria_type',
        'is_active'
    ];

    protected $casts = [
        'weight' => 'float',
        'is_active' => 'boolean'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            // Clear the cache when a weight setting is saved
            $service = app(KosanRecommendationService::class);
            $service->clearCache();
        });

        static::deleted(function ($model) {
            // Clear the cache when a weight setting is deleted
            $service = app(KosanRecommendationService::class);
            $service->clearCache();
        });
    }

    /**
     * Get the display name for the criteria
     */
    /**
     * Get the display name for the criteria
     */
    public function getDisplayNameAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->criteria_name));
    }

    /**
     * Get the criteria type as a human-readable label
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->criteria_type === 'benefit' ? 'Benefit' : 'Cost';
    }

    /**
     * Get the criteria type with a colored badge
     */
    public function getTypeBadgeAttribute(): string
    {
        $class = $this->criteria_type === 'benefit' ? 'success' : 'danger';
        return sprintf(
            '<span class="badge badge-%s">%s</span>',
            $class,
            $this->type_label
        );
    }

    /**
     * Scope a query to only include active settings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive settings.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get the total sum of all active weights
     */
    public static function getTotalActiveWeight(): float
    {
        return (float) static::active()->sum('weight');
    }

    /**
     * Validate the model's attributes.
     */
    public function validate()
    {
        $validator = Validator::make($this->attributes, [
            'criteria_name' => ['required', 'string', 'max:255', 'unique:weight_settings,criteria_name,' . $this->id],
            'weight' => ['required', 'numeric', 'min:0', 'max:1'],
            'criteria_type' => ['required', 'in:cost,benefit'],
            'is_active' => ['boolean']
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Additional validation for weight sum
        if ($this->is_active) {
            $currentTotal = static::where('id', '!=', $this->id)
                ->active()
                ->sum('weight');
            
            if (($currentTotal + $this->weight) > 1) {
                throw ValidationException::withMessages([
                    'weight' => 'The sum of all active weights cannot exceed 1.'
                ]);
            }
        }
    }

    /**
     * Save the model to the database.
     */
    public function save(array $options = [])
    {
        $this->validate();
        return parent::save($options);
    }

    /**
     * Initialize default weight settings if none exist
     */
    public static function initializeDefaults()
    {
        if (static::count() === 0) {
            $defaults = [
                ['criteria_name' => 'harga', 'weight' => 0.23, 'criteria_type' => 'cost'],
                ['criteria_name' => 'luas_kamar', 'weight' => 0.18, 'criteria_type' => 'benefit'],
                ['criteria_name' => 'jarak_kampus', 'weight' => 0.18, 'criteria_type' => 'cost'],
                ['criteria_name' => 'keamanan', 'weight' => 0.10, 'criteria_type' => 'benefit'],
                ['criteria_name' => 'kebersihan', 'weight' => 0.08, 'criteria_type' => 'benefit'],
                ['criteria_name' => 'iuran', 'weight' => 0.07, 'criteria_type' => 'cost'],
                ['criteria_name' => 'aturan', 'weight' => 0.05, 'criteria_type' => 'benefit'],
                ['criteria_name' => 'ventilasi', 'weight' => 0.05, 'criteria_type' => 'benefit'],
                ['criteria_name' => 'fasilitas_kamar', 'weight' => 0.05, 'criteria_type' => 'benefit'],
                ['criteria_name' => 'fasilitas_kamar_mandi', 'weight' => 0.03, 'criteria_type' => 'benefit'],
                ['criteria_name' => 'fasilitas_umum', 'weight' => 0.02, 'criteria_type' => 'benefit'],
                ['criteria_name' => 'akses_lokasi_pendukung', 'weight' => 0.06, 'criteria_type' => 'benefit'],
            ];

            foreach ($defaults as $default) {
                static::create($default);
            }
        }
    }


}
