<?php

namespace Database\Factories;

use App\Models\Cohort;
use Illuminate\Database\Eloquent\Factories\Factory;

class CohortFactory extends Factory
{
    protected $model = Cohort::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement([
                'Programme PFPT 2024 - Phase Alpha',
                'Session Continue 2024 - Région Centre',
                'Édition Spéciale : Transition Numérique',
                'PFPT Excellence 2025'
            ]),
            'total_budget' => $this->faker->randomElement([1000000, 2000000, 5000000, 10000000]),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+6 months', '+12 months'),
            'status' => 'active',
        ];
    }
}
