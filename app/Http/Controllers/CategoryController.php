<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('expenses')
            ->withSum('expenses', 'amount')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'icon' => 'required|string|max:10',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Category::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'icon' => 'required|string|max:10',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->expenses()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki pengeluaran.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
