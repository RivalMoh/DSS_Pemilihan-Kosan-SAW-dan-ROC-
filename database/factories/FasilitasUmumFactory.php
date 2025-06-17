<?php

namespace Database\Factories;

use App\Models\FasilitasUmum;
use Illuminate\Database\Eloquent\Factories\Factory;

class FasilitasUmumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FasilitasUmum::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->word,
            'icon' => $this->faker->randomElement(['wifi', 'parking', 'laundry', 'kitchen', 'tv', 'ac', 'fridge']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
