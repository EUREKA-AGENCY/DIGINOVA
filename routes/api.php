<?php

use App\Http\Controllers\Api\SmsApiController;
use Illuminate\Support\Facades\Route;

Route::post('/sms/send', [SmsApiController::class, 'send'])->name('api.sms.send');
