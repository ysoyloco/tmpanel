<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class InvoiceStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Suma faktur', Invoice::sum('amount') . ' PLN')
                ->description('Wszystkie faktury')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
