<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AksesLokasiPendukung extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'akses_lokasi_pendukung';

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
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lokasi',
        'bobot',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bobot' => 'float',
    ];

    /**
     * The kosan that belong to the akses lokasi pendukung.
     */
    public function kosan(): BelongsToMany
    {
        return $this->belongsToMany(
            Kosan::class,
            'kosan_akses_lokasi_pendukung',
            'akses_lokasi_pendukung_id',
            'kosan_id'
        )->withPivot(['count', 'order']);
    }
}
