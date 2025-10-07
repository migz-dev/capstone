<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add any additional seeders to this array (order matters).
        $this->call([
            FacultySeeder::class,
        ]);
    }
}
