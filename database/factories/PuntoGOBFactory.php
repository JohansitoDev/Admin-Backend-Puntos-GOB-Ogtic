<?php

namespace Database\Factories;

use App\Models\PuntoGOB;
use Illuminate\Database\Eloquent\Factories\Factory;

class PuntoGOBFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = PuntoGOB::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company() . ' Punto GOB',
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}