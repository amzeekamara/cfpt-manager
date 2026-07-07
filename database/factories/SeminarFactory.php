<?php

namespace Database\Factories;

use App\Models\Seminar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seminar>
 */
class SeminarFactory extends Factory
{
    protected $model = Seminar::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-3 months', '+6 months');
        $endDate = clone $startDate;
        $endDate->modify('+' . rand(1, 4) . ' days');

        return [
            'title' => $this->faker->randomElement([
                'Leadership Éducatif & Management',
                'IA dans l\'Enseignement',
                'Médiation Scolaire',
                'Pédagogies Inclusives',
                'Neurosciences éducatives',
                'Gestion du stress pour enseignants',
                'Numérique à l\'école : Enjeux et outils',
                'Évaluation formative et différenciation',
                'Le jeu en classe : Pourquoi et comment ?',
                'Co-enseignement : Pratiques et défis'
            ]),
            'description' => $this->faker->paragraph(),
            'department_name' => $this->faker->randomElement([
                'Département de Mathématiques',
                'Département de Langues',
                'Département des Sciences',
                'Département de Technologie',
                'Administration Centrale'
            ]),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_participants' => $this->faker->randomElement([15, 20, 30, 50]),
            'status' => $this->faker->randomElement(['draft', 'active', 'completed']),
        ];
    }
}
