<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    private function autoTranslateLabels()
    {
        $this->translateLabels([
            Field::class,
            BaseFilter::class,
            Placeholder::class,
            Column::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->autoTranslateLabels();

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'pl']);
        });
    }

    private function translateLabels(array $components = [])
    {
        foreach ($components as $component) {
            $component::configureUsing(function ($c): void {
                $c->translateLabel();
            });
        }
    }
}
