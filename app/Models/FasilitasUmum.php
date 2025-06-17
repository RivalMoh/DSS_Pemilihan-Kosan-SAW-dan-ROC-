<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FasilitasUmum extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'fasilitas_umum';

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
        'nama_fasilitas',
        'bobot',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'bobot' => 'float',
    ];

    /**
     * The kosan that belong to the fasilitas umum.
     */
    public function kosan(): BelongsToMany
    {
        return $this->belongsToMany(
            Kosan::class,
            'kosan_fasilitas_umum',
            'Id_fasilitas',
            'Id_kosan'
        )->withPivot('Nilai_x_Bobot');
    }
}
