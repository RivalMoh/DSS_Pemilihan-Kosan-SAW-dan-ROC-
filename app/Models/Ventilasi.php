<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ventilasi extends Model
{
    protected $table = 'ventilasi';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kondisi_ventilasi',
        'nilai'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'nilai' => 'float',
    ];

    /**
     * Get all kosan with this ventilasi.
     */
    public function kosan()
    {
        return $this->hasMany(Kosan::class, 'Id_ventilasi', 'Id_ventilasi');
    }
}
