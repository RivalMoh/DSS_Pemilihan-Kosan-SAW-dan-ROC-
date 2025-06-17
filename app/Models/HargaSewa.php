<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaSewa extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'harga_sewa';

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
        'rentang_harga',
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
