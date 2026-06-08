<?php

namespace Database\Factories\Client;

use App\Models\Client\Client;
use App\Models\Rcheck\Radcheck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ClientFactory extends Factory
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
            'nombre_completo' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'plan' => $this->faker->randomElement([' ']),
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
            'observaciones' => $this->faker->sentence(),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Client $client) {
            Radcheck::factory()->create([
                'username' => $client->username,  // Usa el mismo username del cliente
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $this->faker->password(),
            ]);
        });
    }
}
