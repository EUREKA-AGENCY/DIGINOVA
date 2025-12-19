<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $items = Category::orderBy('name')->paginate(20)->withQueryString();
        return Inertia::render('admin/CategoriesIndex', ['items' => $items]);
    }

    public function create()
    {
        return Inertia::render('admin/categories/Form', [
            'item' => new Category(),
            'parents' => Category::orderBy('name')->get(['id','name']),
        ]);
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée');
    }

    public function edit(Category $category)
    {
        return Inertia::render('admin/categories/Form', [
            'item' => $category,
            'parents' => Category::orderBy('name')->get(['id','name']),
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée');
    }
}

