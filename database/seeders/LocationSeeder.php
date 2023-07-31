<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::insert([
            ['name' => 'Location 1'],
            ['name' => 'Location 2'],
            ['name' => 'Location 3'],
        ]);

        foreach (User::all() as $user) {
            $user->locations()->attach(Location::pluck('id')->toArray());
        }
    }
}
