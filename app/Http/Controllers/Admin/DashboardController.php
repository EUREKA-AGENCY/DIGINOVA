<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // app/Http/Controllers/Admin/DashboardController.php
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'contacts' => ContactRequest::count(),
                'quotes' => QuoteRequest::count(),
                'services' => Service::count()
            ]
        ]);
    }
}
