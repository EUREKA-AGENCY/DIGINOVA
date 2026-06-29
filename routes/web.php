<?php

use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/messagerie-pro', function () {
    return Inertia::render('MessageriePro');
})->name('messagerie-pro');

Route::get('/sms', function () {
    return Inertia::render('Sms');
})->name('sms');

Route::get('/mentions-legales', function () {
    return Inertia::render('MentionsLegales');
})->name('mentions-legales');

Route::get('/confidentialite', function () {
    return Inertia::render('Confidentialite');
})->name('confidentialite');

Route::get('/sitemap.xml', function () {
    $lastmod = '2026-06-20';
    $base = config('app.url');
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $xml .= "<url><loc>{$base}/</loc><lastmod>{$lastmod}</lastmod><changefreq>monthly</changefreq><priority>1.0</priority></url>";
    $xml .= "<url><loc>{$base}/messagerie-pro</loc><lastmod>{$lastmod}</lastmod><changefreq>monthly</changefreq><priority>0.9</priority></url>";
    $xml .= "<url><loc>{$base}/sms</loc><lastmod>{$lastmod}</lastmod><changefreq>monthly</changefreq><priority>0.9</priority></url>";
    $xml .= "<url><loc>{$base}/mentions-legales</loc><lastmod>{$lastmod}</lastmod><changefreq>yearly</changefreq><priority>0.3</priority></url>";
    $xml .= "<url><loc>{$base}/confidentialite</loc><lastmod>{$lastmod}</lastmod><changefreq>yearly</changefreq><priority>0.3</priority></url>";
    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

Route::post('/contact', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:50',
        'project_type' => 'nullable|string|max:255',
        'budget' => 'nullable|string|max:255',
        'message' => 'required|string|max:5000',
    ]);

    Log::channel('daily')->info('Nouvelle demande de contact Diginova', $data);

    return response()->json(['success' => true, 'message' => 'Demande reçue.']);
})->name('contact');

Route::get('/diagnostic', [DiagnosticController::class, 'create'])->name('diagnostic.create');
Route::post('/diagnostic', [DiagnosticController::class, 'store'])->name('diagnostic.store');

Route::get('/paiement', function () {
    return Inertia::render('Paiement');
})->name('paiement');

Route::get('/hebergement', function () {
    return Inertia::render('Hebergement');
})->name('hebergement');

Route::get('/hebergement/commander', [InvoiceController::class, 'create'])->name('hebergement.commander.create');
Route::post('/hebergement/commander', [InvoiceController::class, 'store'])->name('hebergement.commander.store');

Route::get('/messagerie-pro/assistant-ia', function () {
    return Inertia::render('Addons/AssistantIA');
})->name('addons.assistant-ia');

Route::get('/messagerie-pro/automatisation', function () {
    return Inertia::render('Addons/Automatisation');
})->name('addons.automatisation');

Route::get('/messagerie-pro/audit', function () {
    return Inertia::render('Addons/Audit');
})->name('addons.audit');

Route::get('/messagerie-pro/pack-demarrage', function () {
    return Inertia::render('Addons/PackDemarrage');
})->name('addons.pack-demarrage');

Route::redirect('/dashboard', '/mail')->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
require __DIR__.'/mail.php';
require __DIR__.'/sms-pro.php';
require __DIR__.'/admin.php';
