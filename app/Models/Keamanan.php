<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keamanan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keamanan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_keamanan',
        'nilai',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nilai' => 'float',
    ];

    /**
     * Get all kosan with this keamanan level.
     */
    public function kosan()
    {
        return $this->hasMany(Kosan::class, 'Keamanan', 'Id_keamanan');
    }
}
