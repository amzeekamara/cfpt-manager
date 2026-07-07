<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'paid', 'refunded']);
        $isPaid = $status === 'paid';

        return [
            'enrollment_id' => Enrollment::factory(),
            'amount' => $this->faker->randomElement([150, 350, 450, 1200]),
            'status' => $status,
            'transaction_id' => $isPaid ? 'TX-' . $this->faker->unique()->numerify('##########') : null,
            'payment_date' => $isPaid ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
