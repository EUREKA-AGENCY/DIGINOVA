<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/clients', [AdminController::class, 'storeClient'])->name('clients.store');
    Route::post('/clients/{client}/mail-domain', [AdminController::class, 'assignDomain'])->name('clients.assign-domain');
    Route::delete('/mail-domains/{domain}/owner', [AdminController::class, 'unassignDomain'])->name('mail-domains.unassign');
    Route::post('/clients/{client}/sms-credits', [AdminController::class, 'adjustSmsCredits'])->name('clients.sms-credits');
    Route::put('/clients/{client}', [AdminController::class, 'updateClient'])->name('clients.update');
    Route::post('/clients/{client}/block', [AdminController::class, 'blockClient'])->name('clients.block');
    Route::post('/clients/{client}/unblock', [AdminController::class, 'unblockClient'])->name('clients.unblock');
    Route::delete('/clients/{client}', [AdminController::class, 'destroyClient'])->name('clients.destroy');
    Route::post('/mail-domains', [AdminController::class, 'storeDomain'])->name('mail-domains.store');
});
