<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;

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
            // or even `BaseAction::class`,
        ]);
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->autoTranslateLabels();

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'pl']) // also accepts a closure
            ;
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
