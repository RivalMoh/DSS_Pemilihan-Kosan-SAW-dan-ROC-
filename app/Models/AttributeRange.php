<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AttributeRange extends Model
{
    protected $table = 'attribute_ranges';
    
    protected $fillable = [
        'attribute_name',
        'display_name',
        'min_value',
        'max_value',
        'number_of_groups',
        'group_ranges',
        'is_active'
    ];

    protected $casts = [
        'min_value' => 'float',
        'max_value' => 'float',
        'number_of_groups' => 'integer',
        'group_ranges' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->isDirty(['min_value', 'max_value', 'number_of_groups'])) {
                $model->calculateGroupRanges();
            }
        });
    }

    /**
     * Calculate the group ranges based on min, max and number of groups
     */
    public function calculateGroupRanges(): void
    {
        if (is_null($this->min_value) || is_null($this->max_value) || $this->number_of_groups <= 0) {
            $this->group_ranges = null;
            return;
        }

        $range = $this->max_value - $this->min_value;
        $groupSize = $range / $this->number_of_groups;
        $ranges = [];

        for ($i = 0; $i < $this->number_of_groups; $i++) {
            $start = $this->min_value + ($i * $groupSize);
            $end = ($i === $this->number_of_groups - 1) 
                ? $this->max_value 
                : $start + $groupSize;
            
            $ranges[] = [
                'group' => $i + 1,
                'min' => round($start, 2),
                'max' => round($end, 2),
                'label' => $this->generateRangeLabel($start, $end)
            ];
        }

        $this->group_ranges = $ranges;
    }

    /**
     * Generate a human-readable label for a range
     */
    private function generateRangeLabel(float $min, float $max): string
    {
        if ($this->attribute_name === 'harga_sewa') {
            return 'Rp ' . number_format($min, 0, ',', '.') . ' - ' . 
                   'Rp ' . number_format($max, 0, ',', '.');
        } elseif ($this->attribute_name === 'estimasi_jarak') {
            return number_format($min, 1, ',', '.') . ' - ' . 
                   number_format($max, 1, ',', '.') . ' km';
        } else {
            return number_format($min, 1, ',', '.') . ' - ' . 
                   number_format($max, 1, ',', '.') . ' m²';
        }
    }

    /**
     * Get the score for a given value based on its group
     */
    public function getScoreForValue($value): ?int
    {
        if (empty($this->group_ranges)) {
            return null;
        }

        foreach ($this->group_ranges as $range) {
            if ($value >= $range['min'] && $value <= $range['max']) {
                return $range['group'];
            }
        }

        return null;
    }
}
