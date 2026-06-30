<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminInvoiceController;
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

    Route::get('/factures', [AdminInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/factures/companies', [AdminInvoiceController::class, 'companies'])->name('invoices.companies');
    Route::post('/factures', [AdminInvoiceController::class, 'storeManual'])->name('invoices.store-manual');
    Route::post('/factures/{invoice}/payee', [AdminInvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    Route::post('/factures/{invoice}/annulee', [AdminInvoiceController::class, 'markCancelled'])->name('invoices.mark-cancelled');
    Route::get('/factures/{invoice}/telecharger', [AdminInvoiceController::class, 'download'])->name('invoices.download');
    Route::post('/factures/{invoice}/renvoyer', [AdminInvoiceController::class, 'resend'])->name('invoices.resend');
});
