<?php

namespace Database\Factories\Client;

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
            //'plan' => $this->faker->randomElement(['Básico', 'Premium', 'Empresarial']),
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
}
