<?php

namespace Database\Factories;

use App\Models\FasilitasKamar;
use Illuminate\Database\Eloquent\Factories\Factory;

class FasilitasKamarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FasilitasKamar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->word,
            'icon' => $this->faker->randomElement(['bed', 'desk', 'chair', 'wardrobe', 'air-conditioner']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
