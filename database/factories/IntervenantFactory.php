<?php

namespace Database\Factories;

use App\Models\Intervenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntervenantFactory extends Factory
{
    protected $model = Intervenant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'remuneration' => $this->faker->randomElement([50000, 100000, 150000, 250000]),
            'payment_status' => $this->faker->randomElement(['paid', 'unpaid']),
        ];
    }
}
