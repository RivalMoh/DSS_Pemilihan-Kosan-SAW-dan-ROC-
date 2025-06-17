<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kosan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kosan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'UniqueID';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UniqueID',
        'nama',
        'alamat',
        'harga',
        'luas_kamar',
        'Jarak_kampus',
        'keamanan_id',
        'kebersihan_id',
        'iuran_id',
        'aturan_id',
        'ventilasi_id',
        'deskripsi',
        'foto_utama',
        'jumlah_kamar_tersedia',
        'tipe_kost',
        'user_id',
        'created_at',
        'updated_at'
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['harga_formatted', 'foto_utama_url'];
    
    /**
     * Get the formatted harga attribute.
     *
     * @return string
     */
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
    
    /**
     * Get the full URL for the foto_utama.
     *
     * @return string
     */
    public function getFotoUtamaUrlAttribute()
    {
        if (empty($this->foto_utama)) {
            return asset('storage/kosan/default.jpg');
        }
        
        // Ensure the URL is absolute
        if (str_starts_with($this->foto_utama, 'http')) {
            return $this->foto_utama;
        }
        
        return asset('storage/' . ltrim($this->foto_utama, '/'));
    }
    
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'user',
        'keamanan',
        'kebersihan',
        'iuran',
        'aturan',
        'ventilasi',
        'fasilitas_kamar',
        'fasilitas_kamar_mandi',
        'fasilitas_umum',
        'foto',
    ];
    
    /**
     * Get the user that owns the kosan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'harga' => 'decimal:2',
        'luas_kamar' => 'decimal:2',
        'Jarak_kampus' => 'decimal:2',
    ];

    /**
     * Get the keamanan that owns the kosan.
     */
    public function keamanan(): BelongsTo
    {
        return $this->belongsTo(Keamanan::class, 'keamanan_id');
    }

    /**
     * Get the kebersihan that owns the kosan.
     */
    public function kebersihan(): BelongsTo
    {
        return $this->belongsTo(Kebersihan::class, 'kebersihan_id');
    }

    /**
     * Get the aturan that owns the kosan.
     */
    public function aturan(): BelongsTo
    {
        return $this->belongsTo(Aturan::class, 'aturan_id');
    }

    /**
     * Get the iuran that owns the kosan.
     */
    public function iuran(): BelongsTo
    {
        return $this->belongsTo(Iuran::class, 'iuran_id');
    }

    /**
     * Get the ventilasi that owns the kosan.
     */
    public function ventilasi(): BelongsTo
    {
        return $this->belongsTo(Ventilasi::class, 'ventilasi_id');
    }

    /**
     * Get the fasilitas kamar for the kosan.
     */
    public function fasilitas_kamar()
    {
        return $this->belongsToMany(FasilitasKamar::class, 'kosan_fasilitas_kamar', 'kosan_id', 'fasilitas_kamar_id')
            ->withPivot('order')
            ->orderByPivot('order', 'desc')
            ->withTimestamps();
    }

    /**
     * Get the fasilitas kamar mandi for the kosan.
     */
    public function fasilitas_kamar_mandi()
    {
        return $this->belongsToMany(FasilitasKamarMandi::class, 'kosan_fasilitas_kamar_mandi', 'kosan_id', 'fasilitas_kamar_mandi_id')
            ->withPivot('order')
            ->orderByPivot('order', 'desc')
            ->withTimestamps();
    }

    /**
     * Get the fasilitas umum for the kosan.
     */
    public function fasilitas_umum()
    {
        return $this->belongsToMany(FasilitasUmum::class, 'kosan_fasilitas_umum', 'kosan_id', 'fasilitas_umum_id')
            ->withPivot('order')
            ->orderByPivot('order', 'desc')
            ->withTimestamps();
    }
    
    /**
     * Get the akses lokasi pendukung for the kosan.
     */
    public function akses_lokasi_pendukung()
    {
        return $this->belongsToMany(
            AksesLokasiPendukung::class,
            'kosan_akses_lokasi_pendukung',
            'kosan_id',
            'akses_lokasi_pendukung_id'
        )->withPivot(['count', 'order'])
        ->orderByPivot('order', 'asc')
        ->withTimestamps();
    }

    /**
     * Get all photos for the kosan.
     */
    public function foto(): HasMany
    {
        return $this->hasMany(FotoKosan::class, 'kosan_id');
    }

    /**
     * Get the formatted price attribute.
     *
     * @return string
     */
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Get the formatted luas kamar attribute.
     *
     * @return string
     */
    public function getFormattedLuasKamarAttribute(): string
    {
        return $this->luas_kamar . ' m²';
    }

    /**
     * Get the formatted jarak kampus attribute.
     *
     * @return string
     */
    public function getFormattedJarakKampusAttribute(): string
    {
        return $this->Jarak_kampus . ' km';
    }


    /**
     * Calculate the total score for SAW method
     *
     * @return float
     */
    public function calculateSawScore(): float
    {
        // Implement SAW calculation based on criteria weights
        // This is a simplified example - adjust based on your specific requirements
        $hargaScore = $this->harga * 0.3; // Lower is better
        $luasScore = $this->luas_kamar * 0.25; // Higher is better
        $jarakScore = $this->Jarak_kampus * 0.2; // Lower is better
        $keamananScore = $this->keamanan->nilai * 0.15;
        $kebersihanScore = $this->kebersihan->nilai * 0.1;

        return ($hargaScore * -1) + $luasScore + ($jarakScore * -1) + $keamananScore + $kebersihanScore;
    }

    /**
     * Calculate the total score for ROC method
     *
     * @return float
     */
    public function calculateRocScore(): float
    {
        // Implement ROC calculation
        // This is a simplified example - adjust based on your specific requirements
        $hargaScore = (1 / $this->harga) * 0.4;
        $luasScore = $this->luas_kamar * 0.3;
        $jarakScore = (1 / $this->Jarak_kampus) * 0.2;
        $keamananScore = $this->keamanan->nilai * 0.05;
        $kebersihanScore = $this->kebersihan->nilai * 0.05;

        return $hargaScore + $luasScore + $jarakScore + $keamananScore + $kebersihanScore;
    }
}
