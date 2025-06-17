<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimasiJarak extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'estimasi_jarak';

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
        'rentang_jarak',
        'bobot',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'bobot' => 'float',
    ];
}
