<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Register your seeders here
        $this->call([
            UsersTableSeeder::class,
            SubjectsTableSeeder::class,
            // 各 Seeder を呼び出す
        ]);
    }
}
