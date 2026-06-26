<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/clients', [AdminController::class, 'storeClient'])->name('clients.store');
    Route::post('/clients/{client}/mail-domain', [AdminController::class, 'assignDomain'])->name('clients.assign-domain');
    Route::delete('/mail-domains/{domain}/owner', [AdminController::class, 'unassignDomain'])->name('mail-domains.unassign');
    Route::post('/clients/{client}/sms-credits', [AdminController::class, 'adjustSmsCredits'])->name('clients.sms-credits');
});
