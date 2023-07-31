<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::insert([
            ['id' => 1, 'display_name' => 'people', 'name' => 'people'],
            ['id' => 2, 'display_name' => 'vehicles', 'name' => 'vehicles'],
            ['id' => 3, 'display_name' => 'leakage', 'name' => 'leakage'],
            ['id' => 4, 'display_name' => 'fire', 'name' => 'fire'],
            ['id' => 5, 'display_name' => 'smoke', 'name' => 'smoke'],
            ['id' => 6, 'display_name' => 'change detection', 'name' => 'change'],
        ]);
    }
}
