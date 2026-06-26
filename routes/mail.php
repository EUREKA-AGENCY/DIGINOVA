<?php

use App\Http\Controllers\MailAccountController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('mail')->name('mail.')->group(function () {
    Route::get('/', [MailAccountController::class, 'index'])->name('index');
    Route::post('/{domain}/accounts', [MailAccountController::class, 'store'])->name('accounts.store');
    Route::delete('/{domain}/accounts/{account}', [MailAccountController::class, 'destroy'])->name('accounts.destroy');
    Route::put('/{domain}/accounts/{account}/password', [MailAccountController::class, 'updatePassword'])->name('accounts.password');
    Route::post('/{domain}/catch-all', [MailAccountController::class, 'enableCatchAll'])->name('catch-all.enable');
    Route::delete('/{domain}/catch-all', [MailAccountController::class, 'disableCatchAll'])->name('catch-all.disable');
});
