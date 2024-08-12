<?php
namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\PaymentResource\Pages;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Your form schema here
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('user_id', auth('web')->id());
            })
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('filament.columns.date'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('M d, Y')),
                TextColumn::make('payment_id')
                    ->label(__('filament.columns.payment_id')),
                TextColumn::make('amount')
                    ->label(__('filament.columns.amount'))
                    ->formatStateUsing(fn($state) => 'PLN ' . number_format($state, 2)),
                TextColumn::make('status')
                    ->label(__('filament.columns.status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'fail' => 'danger',
                    }),
            ])
            ->filters([])
            ->actions([
                Action::make('downloadInvoice')
                    ->label(__('filament.download_invoice'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Payment $record) {
                        // Generate or retrieve the PDF for the selected payment
                        $pdf = \PDF::loadView('invoices.invoice', ['payment' => $record]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, "invoice_{$record->payment_id}.pdf");
                    }),
            ])
            ->bulkActions([
                // Your bulk actions here
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Your relations here
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.payment');
    }

    public function getTitle(): string | Htmlable
    {
        return __('filament.payment');
    }

    protected static ?string $label = null;

    public static function getLabel(): string
    {
        return __('filament.payment');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            // Your other pages here
        ];
    }
}
