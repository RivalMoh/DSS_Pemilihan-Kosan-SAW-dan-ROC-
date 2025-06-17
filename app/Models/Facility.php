<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'weight',
        'icon',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weight' => 'integer',
    ];

    /**
     * The kosan that belong to the facility.
     */
    public function kosans(): BelongsToMany
    {
        return $this->belongsToMany(Kosan::class, 'kosan_facility')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include facilities of a given type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the display name of the facility type.
     */
    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'kamar' => 'Kamar',
            'kamar_mandi' => 'Kamar Mandi',
            'umum' => 'Umum',
            default => ucfirst($this->type),
        };
    }
}
