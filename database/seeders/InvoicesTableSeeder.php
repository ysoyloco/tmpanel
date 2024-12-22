<?php
namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvoicesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Invoice::create([
                'user_id' => $user->id,
                'amount' => rand(100, 1000),
                'status' => ['booked', 'cancelled', 'processing'][rand(0, 2)],
                'received_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
