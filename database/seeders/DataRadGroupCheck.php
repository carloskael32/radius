<?php

namespace Database\Seeders;
use App\Models\Rcheck\Radgroupcheck;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataRadGroupCheck extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         RadGroupCheck::create(
            [
                'groupname' => 'inactivo',
                'attribute' => 'Auth-Type',
                'op' => ':=',
                'value' => 'Reject',
            ]
        );
    }
}
