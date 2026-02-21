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
            //
             'username' => $this->faker->unique()->userName(),
            'nombre_completo' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'plan' => $this->faker->randomElement([' ']),
            //'velocidad_subida' => $this->faker->randomFloat(2, 10, 100),
            //'velocidad_bajada' => $this->faker->randomFloat(2, 50, 500),
            //'tipo_conexion' => $this->faker->randomElement(['Fibra', 'ADSL', 'Wireless']),
            //'ip_asignada' => $this->faker->ipv4(),
            //'mac_address' => $this->faker->macAddress(),
            //'fecha_instalacion' => $this->faker->date(),
            //'fecha_corte' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
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
