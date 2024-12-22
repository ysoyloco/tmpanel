<?php

namespace App\Filament\Resources\HelpdeskResource\Pages;

use App\Filament\Resources\HelpdeskResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateHelpdesk extends CreateRecord
{
    protected static string $resource = HelpdeskResource::class;

    protected function afterCreate(): void
    {
        Notification::make()
            ->success()
            ->title('Utworzono zgłoszenie')
            ->body('Kliknij na "Odpowiedz" by dodać pierwszą wiadomość')
            ->persistent()
            ->send();

        $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
    }
}
