<?php

namespace Database\Seeders;

use App\Models\Drone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DroneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Drone::insert([
            ['name' => 'ATLAS-V', 'number' => 'RPAAV-4044'],
            ['name' => 'ATLAS-T', 'number' => 'RPAAT-4045'],
        ]);
    }
}
