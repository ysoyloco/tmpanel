<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Create customer user
        User::create([
            'name' => 'Customer',
            'email' => 'customer@mail.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // Run other seeders
        $this->call([
            PaymentsTableSeeder::class,
            InvoicesTableSeeder::class,
            HelpdeskTableSeeder::class,
        ]);
    }
}