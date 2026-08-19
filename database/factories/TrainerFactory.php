<?php

namespace Database\Factories;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerFactory extends Factory
{
    protected $model = Trainer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'remuneration' => $this->faker->randomElement([50000, 100000, 150000, 250000]),
            'payment_status' => $this->faker->randomElement(['paid', 'unpaid']),
        ];
    }
}
