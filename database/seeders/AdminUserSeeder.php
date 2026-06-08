<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = User::create(
            [
                "username" => "admin",
                "nombres" =>"administrador",
                "apellidos" => "administrador",
                "email" => "admin@gmail.com",
                "estado" => 'activo',
                "password" => Hash::make('admin'),
            ]
        );

        //asignar rol al administrador
        $admin->assignRole('Administrador');
    }
}
