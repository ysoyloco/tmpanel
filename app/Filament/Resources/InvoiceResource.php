<?php
namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Invoice;
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
use App\Filament\Resources\InvoiceResource\Pages;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Faktury';
    protected static ?string $modelLabel = 'Faktura'; 
    protected static ?string $pluralModelLabel = 'Faktury';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('received_at')
                    ->label('Data wpłynięcia')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                
                TextColumn::make('user.name')
                    ->label('Imię i nazwisko')
                    ->visible(fn() => auth()->user()->isAdmin()),
                
                TextColumn::make('user.email')
                    ->label('Email')
                    ->visible(fn() => auth()->user()->isAdmin()),
                
                TextColumn::make('amount')
                    ->label('Kwota')
                    ->money('pln')
                    ->sortable(),
                
            ])
            ->actions([
                Action::make('downloadInvoice')
                    ->label('Pobierz fakturę')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Invoice $record) {
                        $pdf = PDF::loadView('invoices.invoice', ['invoice' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "faktura_{$record->id}.pdf"
                        );
                    }),
            
            ]);    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
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
