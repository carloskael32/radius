<?php

namespace Database\Factories\Rcheck;

use App\Models\Rcheck\Radcheck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rcheck\Radcheck>
 */
class RadcheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'attribute' => 'Cleartext-Password',
            'op' => ':=',
            'value' => $this->faker->password(),
        ];
    }
}
