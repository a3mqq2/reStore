<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::with('product')->paginate();
        return view('discounts.index', compact('discounts'));
    }

    public function create()
    {
        $products = Product::all();
        return view('discounts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Discount::create($request->all());

        return redirect()->route('discounts.index')->with('success', 'تم إضافة الخصم بنجاح');
    }

    public function edit(Discount $discount)
    {
        $products = Product::all();
        return view('discounts.edit', compact('discount', 'products'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $discount->update($request->all());

        return redirect()->route('discounts.index')->with('success', 'تم تعديل الخصم بنجاح');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'تم حذف الخصم بنجاح');
    }
}
