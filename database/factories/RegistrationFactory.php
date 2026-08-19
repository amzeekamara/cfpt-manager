<?php

namespace Database\Factories;

use App\Models\Registration;
use App\Models\Seminar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'seminar_id' => Seminar::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'waiting_list']),
        ];
    }
}
