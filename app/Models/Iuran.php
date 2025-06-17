<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Iuran extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'iuran';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kategori',
        'nilai',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'nilai' => 'integer',
    ];

    /**
     * Get all kosan with this payment type
     */
    public function kosans(): HasMany
    {
        return $this->hasMany(Kosan::class, 'iuran_id');
    }

    /**
     * Get the name and value as a combined string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->kategori} (Nilai: {$this->nilai})";
    }
}
