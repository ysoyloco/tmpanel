<?php

namespace App\Filament\Resources\HelpdeskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Section;
use Illuminate\Contracts\View\View;
use Filament\Actions;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';
    protected static ?string $title = 'Konwersacja';
    protected static ?string $recordTitleAttribute = 'content';

    public function render(): View
    {
        return view('filament.resources.helpdesk.messages', [
            'messages' => $this->getRelationship()->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Wiadomość')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label('Treść')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('direction')
                            ->label('Kierunek')
                            ->options([
                                'incoming' => 'Przychodzące',
                                'outgoing' => 'Wychodzące'
                            ])
                            ->default('outgoing')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->default(fn() => auth()->user()->email)
                            ->required()
                    ])
            ]);
    }
}
