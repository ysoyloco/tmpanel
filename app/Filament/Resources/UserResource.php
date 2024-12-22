<?php
namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Użytkownicy';
    protected static ?string $modelLabel = 'Użytkownik';
    protected static ?string $pluralModelLabel = 'Użytkownicy';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nazwa')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                IconColumn::make('is_admin')
                    ->boolean()
                    ->label('Administrator'),
            ])
            ->actions([
                Action::make('impersonate')
                    ->label('Zaloguj jako')
                    ->icon('heroicon-o-identification')
                    ->action(fn (User $user) => auth()->login($user))
                    ->visible(fn () => auth()->user()->isAdmin())
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Administracja';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin();
    }


public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('name')
                ->required()
                ->label('Nazwa'),
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Email'),
            Toggle::make('is_admin')
                ->label('Administrator')
                ->visible(fn () => auth()->user()->isAdmin()),
            TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create')
                ->label('Hasło'),
        ]);
}


public static function getPages(): array
{
    return [
        'index' => Pages\ListUsers::route('/'),
        'create' => Pages\CreateUser::route('/create'),
        'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
}


public static function shouldRegisterNavigation(): bool
{
    return auth()->user()->isAdmin();
}
}