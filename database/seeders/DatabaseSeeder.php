<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Report\Database\Seeders\ReportDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(UserRolePermissionSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(DetectionTypeSeeder::class);
        $this->call(DroneSeeder::class);
        $this->call(ReportDatabaseSeeder::class);
    }
}