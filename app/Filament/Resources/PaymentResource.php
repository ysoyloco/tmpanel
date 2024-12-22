<?php
namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\PaymentResource\Pages;
use Filament\Notifications\Notification;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Płatności';
    protected static ?string $modelLabel = 'Płatność';
    protected static ?string $pluralModelLabel = 'Płatności';

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('payu')
                    ->label('Nowa płatność PayU')
                    ->icon('heroicon-o-credit-card')
                    ->action(function () {
                        $statuses = ['booked', 'processing', 'cancelled'];
                        $newStatus = $statuses[array_rand($statuses)];
                        
                        Payment::create([
                            'user_id' => auth()->id(),
                            'amount' => rand(100, 1000),
                            'payment_type' => 'payu',
                            'status' => $newStatus,
                            'received_at' => now()
                        ]);

                        $statusMessages = [
                            'booked' => 'Płatność zaksięgowana',
                            'processing' => 'Płatność w trakcie przetwarzania',
                            'cancelled' => 'Płatność anulowana'
                        ];

                        Notification::make()
                            ->title($statusMessages[$newStatus])
                            ->send();
                    })
            ])
            ->columns([
                TextColumn::make('received_at')
                    ->label('Data wpłynięcia')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                    
                TextColumn::make('amount')
                    ->label('Kwota')
                    ->money('pln')
                    ->sortable(),
                    
                TextColumn::make('payment_type')
                    ->label('Metoda płatności')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'payu' => 'PayU',
                        'bank_transfer' => 'Przelew bankowy',
                    }),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'booked' => 'Zaksięgowane',
                        'cancelled' => 'Anulowane',
                        'processing' => 'W trakcie przetwarzania',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'booked' => 'success',
                        'cancelled' => 'danger',
                        'processing' => 'warning',
                    }),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Usuń'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Usuń zaznaczone'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
        ];
    }


public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    
    if (!auth()->user()->isAdmin()) {
        $query->where('user_id', auth()->id());
    }
    
    return $query;
}
}