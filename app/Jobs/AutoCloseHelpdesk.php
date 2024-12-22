<?php
namespace App\Jobs;

use App\Models\Subject;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AutoCloseHelpdesk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $subjects = Subject::where('status', '!=', 'closed')
            ->whereHas('messages', function ($query) {
                $query->where('direction', 'outgoing')
                    ->where('created_at', '<=', now()->subDays(5));
            })
            ->whereDoesntHave('messages', function ($query) {
                $query->where('direction', 'incoming')
                    ->where('created_at', '>', now()->subDays(5));
            })
            ->get();

        foreach ($subjects as $subject) {
            Message::create([
                'subject_id' => $subject->id,
                'content' => 'WIADOMOŚĆ AUTOMATYCZNA - Zgłoszenie zostało automatycznie zamknięte ze względu na brak odpowiedzi.',
                'direction' => 'outgoing',
                'email' => 'system@telemedia.pl'
            ]);

            $subject->update(['status' => 'closed']);
        }
    }
}
