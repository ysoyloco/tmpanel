<?php

namespace App\Filament\Actions;

use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class ImpersonateAction extends Impersonate
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->label('Zaloguj jako')
            ->button();
    }
}
