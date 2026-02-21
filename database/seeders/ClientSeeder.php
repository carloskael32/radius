<?php

namespace Database\Seeders;

use App\Models\Client\Client;
use App\Models\Rcheck\Radcheck;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory(50)->create();
    }
}
