<?php
namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HelpdeskTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('is_admin', true)->first();
        $customer = User::where('email', 'customer@mail.com')->first();

        $subject = Subject::create([
            'user_id' => $customer->id,
            'title' => 'Problem z płatnością PayU',
            'ticket_id' => 'TIC-' . Str::random(8),
            'status' => 'waiting_for_support'
        ]);

        Message::create([
            'subject_id' => $subject->id,
            'content' => 'Dzień dobry, mam problem z płatnością przez PayU. Transakcja się nie powiodła.',
            'direction' => 'incoming',
            'email' => $customer->email
        ]);

        Message::create([
            'subject_id' => $subject->id,
            'content' => 'Dzień dobry, proszę o podanie numeru transakcji PayU.',
            'direction' => 'outgoing',
            'email' => 'support@telemedia.pl'
        ]);

        Message::create([
            'subject_id' => $subject->id,
            'content' => 'Numer transakcji to PAY-2023-12-22-001',
            'direction' => 'incoming',
            'email' => $customer->email
        ]);

        $subject2 = Subject::create([
            'user_id' => $customer->id,
            'title' => 'Pytanie o fakturę',
            'ticket_id' => 'TIC-' . Str::random(8),
            'status' => 'waiting_for_customer'
        ]);

        Message::create([
            'subject_id' => $subject2->id,
            'content' => 'Proszę o wystawienie faktury VAT.',
            'direction' => 'incoming',
            'email' => $customer->email
        ]);

        Message::create([
            'subject_id' => $subject2->id,
            'content' => 'Faktura została wygenerowana. Proszę o potwierdzenie otrzymania.',
            'direction' => 'outgoing',
            'email' => 'support@telemedia.pl'
        ]);
    }
}
