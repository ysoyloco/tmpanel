<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/impersonate/{user}', function (User $user) {
        auth()->user()->impersonate($user);
        return redirect('/admin');
    })->name('impersonate.start');

    Route::get('/impersonate/stop', function () {
        auth()->user()->leaveImpersonation();
        return redirect('/admin');
    })->name('impersonate.stop');
});
