<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home()
    {
        return Inertia::render('public/Home');
    }

    public function about()
    {
        return Inertia::render('public/About');
    }
    public function quote()
    {
        return Inertia::render('public/Quote');
    }

    public function contact()
    {
        return Inertia::render('public/Contact');
    }

    public function achievements()
    {
        return Inertia::render('public/Achievements');
    }
}
