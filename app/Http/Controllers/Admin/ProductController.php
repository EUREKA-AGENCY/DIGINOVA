<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $items = Product::orderBy('name')->paginate(20)->withQueryString();
        return Inertia::render('admin/ProductsIndex', ['items' => $items]);
    }

    public function create()
    {
        return Inertia::render('admin/products/Form', [
            'item' => new Product(),
            'categories' => Category::orderBy('name')->get(['id','name']),
        ]);
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Produit créé');
    }

    public function edit(Product $product)
    {
        return Inertia::render('admin/products/Form', [
            'item' => $product,
            'categories' => Category::orderBy('name')->get(['id','name']),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé');
    }
}

