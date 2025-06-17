<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kebersihan extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'kebersihan';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tingkat_kebersihan',
        'nilai',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'nilai' => 'float',
    ];

    /**
     * Get all kosan with this kebersihan level.
     */
    public function kosan()
    {
        return $this->hasMany(Kosan::class, 'Kebersihan', 'Id_kebersihan');
    }
}
