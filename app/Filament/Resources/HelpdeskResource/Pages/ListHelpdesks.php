<?php
namespace App\Filament\Resources\HelpdeskResource\Pages;

use App\Filament\Resources\HelpdeskResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListHelpdesks extends ListRecords
{
    protected static string $resource = HelpdeskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nowe zgłoszenie')
        ];
    }
}
