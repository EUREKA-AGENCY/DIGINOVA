<?php

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

Route::get('/lab/grid-system', function () {
    return Inertia::render('LabGridSystem');
})->name('lab.grid-system');

Route::get('/sitemap.xml', function () {
    $lastmod = '2026-06-19';
    $base    = config('app.url');
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $xml .= "<url><loc>{$base}/</loc><lastmod>{$lastmod}</lastmod><changefreq>monthly</changefreq><priority>1.0</priority></url>";
    $xml .= "<url><loc>{$base}/messagerie-pro</loc><lastmod>{$lastmod}</lastmod><changefreq>monthly</changefreq><priority>0.9</priority></url>";
    $xml .= "<url><loc>{$base}/sms</loc><lastmod>{$lastmod}</lastmod><changefreq>monthly</changefreq><priority>0.9</priority></url>";
    $xml .= '</urlset>';
    return response($xml, 200)->header('Content-Type', 'application/xml');
});

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
