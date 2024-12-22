<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PaymentStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Suma płatności', Payment::sum('amount') . ' PLN')
                ->description('Wszystkie płatności')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Zaksięgowane', Payment::where('status', 'booked')->sum('amount') . ' PLN')
                ->description('Potwierdzone płatności')
                ->color('success'),

            Stat::make('W trakcie', Payment::where('status', 'processing')->sum('amount') . ' PLN')
                ->description('Oczekujące płatności')
                ->color('warning'),
        ];
    }
}
