<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tipo;
class TipoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Tipo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tip_desc' => $this->faker->sentence(),
            'tip_img' => $this->faker->sentence(),
            'tip_estado' => $this->faker->randomElement(['Activo','Desactivado']),
        ];
    }
}
