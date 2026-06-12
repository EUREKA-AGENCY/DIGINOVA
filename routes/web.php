<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::post('/contact', function (Request $request) {
    $data = $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|max:255',
        'phone'        => 'nullable|string|max:50',
        'project_type' => 'nullable|string|max:255',
        'budget'       => 'nullable|string|max:255',
        'message'      => 'required|string|max:5000',
    ]);

    Log::channel('daily')->info('Nouvelle demande de contact Diginova', $data);

    return response()->json(['success' => true, 'message' => 'Demande reçue.']);
})->name('contact');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
