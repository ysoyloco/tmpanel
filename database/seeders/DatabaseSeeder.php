<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\PaymentsTableSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'customer',
            'email' => 'customer@mail.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'customer2',
            'email' => 'customer2@mail.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            PaymentsTableSeeder::class, // Ensure this line is present
        ]);

    }
}
