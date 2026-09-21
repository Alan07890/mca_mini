<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\diseases_categories;

class CategoryController extends Controller
{
    // Show Add Category Page
    public function category()
    {
        return view('admin.category');
    }

    // Save Category
    public function diseasecategory_insert(Request $request)
    {
        $category = new diseases_categories();

        $category->diseases_name = $request->diseases_name;

        $category->save();

        return redirect()->back()->with('success', 'Disease Category Added Successfully');
    }

    // Display Categories
    public function index()
    {
        $category = diseases_categories::all();

        return view('displaycategory', compact('category'));
    }

    // Display Category View
    public function categoryview()
    {
        $diseases_categories = diseases_categories::all();

        return view('admin.categoryview', compact('diseases_categories'));
    }

    // Edit Category
    public function category_edit($id)
    {
        $category = diseases_categories::where('category_id', $id)->firstOrFail();

        return view('admin.categoryedit', compact('category'));
    }
    // Update Category
    public function category_update(Request $request, $id)
{
    $category = diseases_categories::where('category_id', $id)->firstOrFail();

    $category->diseases_name = $request->diseases_name;

    $category->save();

    return redirect()->route('categoryview')
                     ->with('success', 'Category updated successfully.');
}

    // Delete Category
    public function category_delete($id)
    {
        $category = diseases_categories::where('category_id', $id)->firstOrFail();

        $category->delete();

        return redirect()->route('categoryview')
                         ->with('success', 'Category deleted successfully.');
    }
    
}