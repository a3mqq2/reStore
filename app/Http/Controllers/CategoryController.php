<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::query();

        // Apply filters if provided
        if ($request->filled('name')) {
            $categories->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // You can add more filters as needed   
        $categories = $categories->get();
        $product = Product::with(['requirements','requirements.listItems'])->find(request("product_id"));
        return response(['categories' => $categories,"product" => $product], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Create the new category
        Category::create([
            'name' => $request->name,
        ]);

        // Redirect back with a success message
        return redirect()->route('categories.index')->with('success', 'تم إضافة الفئة بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // Update the category
        $category->update([
            'name' => $request->name,
        ]);

        // Redirect back with a success message
        return redirect()->route('categories.index')->with('success', 'تم تحديث بيانات الفئة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'تم حذف الفئة بنجاح.');
    }

    public function get_payment_methods(Request $request) {
        $paymentMethods = PaymentMethod::with(['currency'])->where('active',1)->get();
        return response($paymentMethods, 200);
    }

    public function get_payment_method($id) {
        $paymentMethod = PaymentMethod::with(['currency'])->where('id', $id)->firstOrFail();
        return response()->json($paymentMethod, 200);
    }
}
