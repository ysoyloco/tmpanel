<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Subject;
use App\Models\User;
use App\Policies\PaymentPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\SubjectPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider


{
    protected $policies = [
        Payment::class => PaymentPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Subject::class => SubjectPolicy::class,
        User::class => UserPolicy::class,
    ];
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
