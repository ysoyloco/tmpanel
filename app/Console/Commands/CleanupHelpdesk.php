<?php

namespace App\Console\Commands;

use App\Models\Subject;
use Illuminate\Console\Command;

class CleanupHelpdesk extends Command
{
    protected $signature = 'helpdesk:cleanup';
    protected $description = 'Remove helpdesk tickets without messages';

    public function handle()
    {
        $count = Subject::whereNotExists(function ($query) {
            $query->select('id')
                ->from('messages')
                ->whereColumn('subject_id', 'subjects.id');
        })->delete();

        $this->info("Removed {$count} empty tickets");
    }
}