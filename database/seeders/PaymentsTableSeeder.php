<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        // Example seeding for 10 payments
        $users = \App\Models\User::all(); // Assuming you have some users already seeded
        foreach ($users as $user) {
            $uuid = Str::uuid()->toString();

            DB::table('payments')->insert([
                'user_id'    => $user->id,
                'payment_id' => substr(md5($uuid), 0, 5), // Generate a unique payment ID
                'plan' => "10-MBPS", // Generate a unique payment ID
                'amount' => 100, // Generate a unique payment ID
                'status'     => 'completed', // Example status
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
