<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RolesAndPermissionsSeeder::class,
            SuperAdminSeeder::class,
            AdminSeeder::class,
            SlidersSeeder::class,
            FooterSeeder::class,
            AboutUsSeeder::class,
            WhyChooseUsSeeder::class,
            ImpactStatSeeder::class
        ]);
    }
}
