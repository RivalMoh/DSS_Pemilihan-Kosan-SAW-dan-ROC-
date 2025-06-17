<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aturan extends Model
{
    protected $table = 'aturan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'jenis_aturan',
        'nilai'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'nilai' => 'float',
    ];

    /**
     * Get all kosan with this aturan.
     */
    public function kosan()
    {
        return $this->hasMany(Kosan::class, 'Id_aturan', 'Id_aturan');
    }
}
