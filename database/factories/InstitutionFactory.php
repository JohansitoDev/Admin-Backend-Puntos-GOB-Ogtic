<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Institution::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company() . ' Institución',
            'acronym' => $this->faker->unique()->word() . Str::random(3),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'website' => $this->faker->url(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}