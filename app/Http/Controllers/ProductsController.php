<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', true);

        if ($request->filled('category')) {
            $slug = $request->string('category');
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('type')) {
            $query->where('product_type', $request->string('type'));
        }

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('short_description', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            });
        }

        // Price filters (sale price or rent price)
        if ($request->filled('price_min')) {
            $min = (float) $request->get('price_min');
            $query->where(function ($q) use ($min) {
                $q->whereNotNull('price')->where('price', '>=', $min)
                  ->orWhere(function ($q2) use ($min) {
                      $q2->whereNotNull('rent_price')->where('rent_price', '>=', $min);
                  });
            });
        }
        if ($request->filled('price_max')) {
            $max = (float) $request->get('price_max');
            $query->where(function ($q) use ($max) {
                $q->whereNotNull('price')->where('price', '<=', $max)
                  ->orWhere(function ($q2) use ($max) {
                      $q2->whereNotNull('rent_price')->where('rent_price', '<=', $max);
                  });
            });
        }

        // Sorting
        $sort = $request->get('sort');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(price, rent_price) asc');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(price, rent_price) desc');
                break;
            case 'created_desc':
                $query->latest();
                break;
            default:
                $query->orderBy('name');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get(['id','name','slug']);

        return Inertia::render('public/Shop/Index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $request->get('category', ''),
            'filter' => $request->get('type', ''),
            'filters' => [
                'type' => $request->get('type', ''),
                'category' => $request->get('category', ''),
                'price_min' => $request->get('price_min', ''),
                'price_max' => $request->get('price_max', ''),
                'sort' => $sort ?: '',
                'q' => $request->get('q', ''),
            ],
        ]);
    }

    public function rentals(Request $request)
    {
        $query = Product::query()
            ->where('is_active', true)
            ->where('product_type', 'rental');

        // Optional category filter for rentals page
        if ($request->filled('category')) {
            $slug = $request->string('category');
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $products = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get(['id','name','slug']);

        return Inertia::render('public/Shop/Index', [
            'products' => $products,
            'categories' => $categories,
            'filter' => 'rental',
            'currentCategory' => $request->get('category', ''),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return Inertia::render('public/Shop/Show', [
            'product' => $product,
        ]);
    }
}
