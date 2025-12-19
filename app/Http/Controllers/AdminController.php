<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Testimonial;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('admin/Dashboard', [
            'counts' => [
                'services' => Service::count(),
                'products' => Product::count(),
                'posts' => BlogPost::count(),
                'testimonials' => Testimonial::count(),
            ],
        ]);
    }

    public function services()
    {
        $items = Service::orderBy('order')->paginate(20)->withQueryString();
        return Inertia::render('admin/ServicesIndex', ['items' => $items]);
    }

    public function products()
    {
        $items = Product::orderBy('name')->paginate(20)->withQueryString();
        return Inertia::render('admin/ProductsIndex', ['items' => $items]);
    }

    public function posts()
    {
        $items = BlogPost::orderByDesc('published_at')->paginate(20)->withQueryString();
        return Inertia::render('admin/PostsIndex', ['items' => $items]);
    }

    public function testimonials()
    {
        $items = Testimonial::latest()->paginate(20)->withQueryString();
        return Inertia::render('admin/TestimonialsIndex', ['items' => $items]);
    }
}

