<?php
namespace Database\Seeders;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Payment::create([
                'user_id' => $user->id,
                'amount' => rand(100, 1000),
                'payment_type' => ['payu', 'bank_transfer'][rand(0, 1)],
                'status' => ['booked', 'cancelled', 'processing'][rand(0, 2)],
                'received_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
