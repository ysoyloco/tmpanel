<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HelpdeskResource\Pages;
use App\Models\Subject;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Filament\Resources\HelpdeskResource\RelationManagers\MessagesRelationManager;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use App\Models\Message;

class HelpdeskResource extends Resource
{
    protected static ?string $model = Subject::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Zgłoszenia';
    protected static ?string $modelLabel = 'Zgłoszenie';
    protected static ?string $pluralModelLabel = 'Zgłoszenia';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Temat zgłoszenia')
                    ->required(),
                Hidden::make('user_id')
                    ->default(fn() => auth()->id()),
                Hidden::make('status')
                    ->default('new'),
                Hidden::make('ticket_id')
                    ->default('TIC-' . \Illuminate\Support\Str::random(8))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->whereExists(function ($query) {
                    $query->select('id')
                        ->from('messages')
                        ->whereColumn('subject_id', 'subjects.id');
                })
                ->select('subjects.*')
                ->selectSub(
                    Message::select('created_at')
                        ->whereColumn('subject_id', 'subjects.id')
                        ->latest()
                        ->limit(1),
                    'last_message_at'
                )
                ->orderByDesc('last_message_at')
            )
            ->columns([
                TextColumn::make('user.email')
                    ->label('Użytkownik')
                    ->searchable(),
                TextColumn::make('ticket_id')
                    ->label('Numer zgłoszenia')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Temat')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'info',
                        'waiting_for_support' => 'warning',
                        'waiting_for_customer' => 'primary',
                        'closed' => 'success',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'new' => 'Nowe',
                        'waiting_for_support' => 'Oczekuje na helpdesk',
                        'waiting_for_customer' => 'Oczekuje na klienta',
                        'closed' => 'Zamknięte',
                    }),
                TextColumn::make('last_message_at')
                    ->label('Data ostatniej wiadomości')
                    ->dateTime('d.m.Y H:i'),
                TextColumn::make('created_at')
                    ->label('Data utworzenia')
                    ->dateTime('d.m.Y H:i'),
            ])
            ->paginated([
                'items_per_page_select_options' => 20,
            ]);
    }
    public static function getRelations(): array
    {
        return [
            MessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHelpdesks::route('/'),
            'create' => Pages\CreateHelpdesk::route('/create'),
            'edit' => Pages\EditHelpdesk::route('/{record}/edit'),
            'view' => Pages\ViewHelpdesk::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        return auth()->user()->isAdmin()
            ? $query
            : $query->where('user_id', auth()->id());
    }
}
