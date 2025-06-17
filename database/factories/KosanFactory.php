<?php

namespace Database\Factories;

use App\Models\Kosan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KosanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kosan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'UniqueID' => 'KSN' . now()->format('YmdHis') . rand(100, 999),
            'nama' => $this->faker->company . ' Kost',
            'alamat' => $this->faker->address,
            'kecamatan' => $this->faker->citySuffix,
            'kota' => $this->faker->city,
            'deskripsi' => $this->faker->paragraph,
            'harga' => $this->faker->numberBetween(500000, 5000000),
            'luas_kamar' => $this->faker->numberBetween(12, 36),
            'Jarak_kampus' => $this->faker->randomFloat(1, 0.1, 10),
            'tipe_kost' => $this->faker->randomElement(['Putri', 'Putra', 'Campur']),
            'jumlah_kamar_tersedia' => $this->faker->numberBetween(1, 20),
            'foto_utama' => 'storage/kosan/default.jpg',
            'is_available' => true,
            'keamanan_id' => 1,
            'kebersihan_id' => 1,
            'ventilasi_id' => 1,
            'iuran_id' => 1,
            'aturan_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the kosan is not available.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unavailable()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_available' => false,
            ];
        });
    }

    /**
     * Indicate the type of kosan.
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function type($type)
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'tipe_kost' => $type,
            ];
        });
    }
}
