<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all();
        return view('product_categories.index', compact('categories'));
    }

    public function create()
    {
        $products = Product::whereNull('product_category_id')->get(); // Fetch all products
        return view('product_categories.create', compact('products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'boolean',
            'products' => 'array', // Ensure products is an array
            'products.*' => 'exists:products,id', // Validate each selected product
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
            $data['image'] = $imagePath;
        }

        // Create the category
        $category = ProductCategory::create($data);

        // Attach selected products
        if ($request->has('products')) {
            $products = Product::find($request->products);
            $products->each(function($p) use($category) {
                $p->update(['product_category_id' => $category->id]);
            });
        }

        return redirect()->route('product_categories.index')->with('success', 'تم إضافة الفئة بنجاح.');
    }




    public function edit(ProductCategory $productCategory)
    {
        $products = Product::all(); // Fetch all products
        $selectedProducts = $productCategory->products->pluck('id')->toArray();
        return view('product_categories.edit', compact('productCategory','products','selectedProducts'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate the image
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($productCategory->image) {
                \Storage::disk('public')->delete($productCategory->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('category_images', 'public');
            $data['image'] = $imagePath;
        }

        $productCategory->update($data);

        return redirect()->route('product_categories.index')->with('success', 'تم تحديث الفئة بنجاح.');
    }


    public function destroy(ProductCategory $productCategory)
    {
        $products = $productCategory->products;
        $products->each(function($p) {
            $p->product_category_id = null;
            $p->save();
        });
        $productCategory->delete();
        return redirect()->route('product_categories.index')->with('success', 'تم حذف الفئة بنجاح.');
    }
}
