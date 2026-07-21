<?php

namespace Database\Seeders;

use App\Models\Rcheck\Radgroupcheck;
use App\Models\Rcheck\Radgroupreply;
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
         RadGroupCheck::create(
            [
                'groupname' => 'suspendidos',
                'attribute' => 'Auth-Type',
                'op' => ':=',
                'value' => 'Accept',
            ]  
        );

        // crear radgroupreply
        Radgroupreply::create(
            [
                'groupname' => 'suspendidos',
                'attribute' => 'Mikrotik-Rate-Limit',
                'op' => ':=',
                'value' => '1M/1M',
            ]
        );
        Radgroupreply::create(
            [
                'groupname' => 'suspendidos',
                'attribute' => 'Mikrotik-Address-List',
                'op' => ':=',
                'value' => 'morosos',
            ]
        );
    }
}
