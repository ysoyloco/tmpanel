<?php

namespace App\Filament\Resources\HelpdeskResource\Pages;

use App\Filament\Resources\HelpdeskResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use App\Models\Message;
use Filament\Forms;
use Filament\Notifications\Notification;

class ViewHelpdesk extends ViewRecord
{
    protected static string $resource = HelpdeskResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Odpowiedz')
                ->icon('heroicon-o-paper-airplane')
                ->modalHeading('Nowa wiadomość')
                ->form([
                    Forms\Components\Textarea::make('content')
                        ->label('Treść')
                        ->required(),
                ])
                ->visible(fn() => $this->record->status !== 'closed')
                ->after(function () {
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                })
                ->action(function (array $data): void {
                    $isAdmin = auth()->user()->isAdmin();
        
                    Message::create([
                        'subject_id' => $this->record->id,
                        'content' => $data['content'],
                        'direction' => $isAdmin ? 'outgoing' : 'incoming',
                        'email' => auth()->user()->email
                    ]);
        
                    $this->record->update([
                        'status' => $isAdmin ? 'waiting_for_customer' : 'waiting_for_support'
                    ]);
                }),
            
            Action::make('close')
                ->label('Zamknij zgłoszenie')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Zamknij zgłoszenie')
                ->modalDescription('Czy na pewno chcesz zamknąć to zgłoszenie?')
                ->modalSubmitActionLabel('Tak, zamknij')
                ->modalCancelActionLabel('Nie, anuluj')
                ->visible(fn() => $this->record->status !== 'closed')
                ->after(function () {
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                })
                ->action(function (): void {
                    $this->record->update(['status' => 'closed']);
                    
                    Notification::make()
                        ->success()
                        ->title('Zgłoszenie zostało zamknięte')
                        ->send();
                }),

            Action::make('reopen')
                ->label('Otwórz ponownie')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Otwórz ponownie zgłoszenie')
                ->modalDescription('Czy na pewno chcesz ponownie otworzyć to zgłoszenie?')
                ->modalSubmitActionLabel('Tak, otwórz')
                ->modalCancelActionLabel('Nie, anuluj')
                ->visible(fn() => $this->record->status === 'closed')
                ->after(function () {
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                })
                ->action(function (): void {
                    $this->record->update(['status' => 'new']);
                    
                    Notification::make()
                        ->success()
                        ->title('Zgłoszenie zostało ponownie otwarte')
                        ->send();
                })
        ];
    }}
