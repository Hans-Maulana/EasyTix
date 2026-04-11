<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function manageCategories()
    {
        $categories = Category::all();
        return view('admin.manage-categories', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.create-category');
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->route('admin.manageCategories')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function editCategory(Category $category)
    {
        return view('admin.edit-category', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->update($request->all());
        return redirect()->route('admin.manageCategories')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function deleteCategory(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('admin.manageCategories')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.manageCategories')->with('error', 'Gagal menghapus: Kategori sedang digunakan pada event.');
        } catch (\Exception $e) {
            return redirect()->route('admin.manageCategories')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
