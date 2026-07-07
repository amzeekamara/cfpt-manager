<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->randomElement([
                'Location de salle',
                'Pauses café et déjeuner',
                'Matériel didactique (stylos, cahiers)',
                'Location de projecteur',
                'Transport équipe administrative'
            ]),
            'amount' => $this->faker->randomElement([15000, 45000, 100000, 25000]),
            'expense_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
