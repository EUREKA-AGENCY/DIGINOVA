<?php

use App\Http\Controllers\SmsAccountController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('sms-pro')->name('sms-pro.')->group(function () {
    Route::get('/', [SmsAccountController::class, 'index'])->name('index');
    Route::get('/send', [SmsAccountController::class, 'showSend'])->name('send.show');
    Route::post('/send', [SmsAccountController::class, 'send'])->name('send');
    Route::get('/history', [SmsAccountController::class, 'history'])->name('history');
    Route::get('/contacts', [SmsAccountController::class, 'contacts'])->name('contacts.index');
    Route::post('/contacts', [SmsAccountController::class, 'storeContact'])->name('contacts.store');
    Route::delete('/contacts/{contact}', [SmsAccountController::class, 'destroyContact'])->name('contacts.destroy');
});
