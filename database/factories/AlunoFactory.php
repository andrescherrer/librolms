<?php

namespace Database\Factories;

use App\Models\Aluno;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aluno>
 */
class AlunoFactory extends Factory
{
    protected $model = Aluno::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'sexo' => $this->faker->randomElement(['masculino', 'feminino']),
            'nascimento' => $this->faker->date(),
        ];
    }
}
