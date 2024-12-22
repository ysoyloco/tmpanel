<?php
namespace App\Console\Commands;

use App\Models\Subject;
use App\Models\Message;
use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use Exception;
use Log;

class CheckHelpdeskMails extends Command
{
    protected $signature = 'helpdesk:check-mails';
    protected $description = 'Check helpdesk mailbox for new messages';

    public function handle()
    {
        try {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolder('INBOX');
            $messages = $folder->messages()->all()->get();

            foreach ($messages as $message) {
                $ticketId = $this->extractTicketId($message->getSubject());
                
                if ($ticketId) {
                    $subject = Subject::where('ticket_id', $ticketId)->first();
                    if ($subject) {
                        Message::create([
                            'subject_id' => $subject->id,
                            'content' => $message->getTextBody(),
                            'direction' => 'incoming',
                            'email' => $message->getFrom()[0]->mail
                        ]);
                        
                        $subject->update(['status' => 'waiting']);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Mail check failed: ' . $e->getMessage());
            $this->error('Mail check failed: ' . $e->getMessage());
        }
    }

    private function extractTicketId(string $subject): ?string
    {
        if (preg_match('/Helpdesk Nowa wiadomość w temacie -(\d{8})/', $subject, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function processMessage($message, Subject $subject): void
    {
        $isCustomerEmail = $message->getFrom()[0]->mail === $subject->user->email;
        
        Message::create([
            'subject_id' => $subject->id,
            'content' => $message->getTextBody(),
            'direction' => $isCustomerEmail ? 'incoming' : 'outgoing',
            'email' => $message->getFrom()[0]->mail
        ]);
        
        $subject->update([
            'status' => $isCustomerEmail ? 'waiting_for_support' : 'waiting_for_customer'
        ]);
    }
}
