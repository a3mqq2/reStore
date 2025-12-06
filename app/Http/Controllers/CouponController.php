<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize the query with relationships for eager loading
        $query = Coupon::with(['product', 'variant']);

        // Apply filter: Search by Coupon Code
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->input('code') . '%');
        }

        // Apply filter: Search by Discount Type
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->input('discount_type'));
        }

        // Apply filter: Search by Active Status
        if ($request->filled('active')) {
            $query->where('active', $request->input('active'));
        }

        // Apply filter: Search by Product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        // Apply filter: Search by Variant
        if ($request->filled('variant_id')) {
            $query->where('variant_id', $request->input('variant_id'));
        }

        // Apply filter: Search by Discount Value
        if ($request->filled('discount_value')) {
            $discountValue = $request->input('discount_value');

            // If Discount Type is specified, filter accordingly
            if ($request->filled('discount_type')) {
                if ($request->input('discount_type') === 'percentage') {
                    $query->where('discount_percentage', $discountValue);
                } elseif ($request->input('discount_type') === 'amount') {
                    $query->where('discount_amount', $discountValue);
                }
            } else {
                // If Discount Type is not specified, search both fields
                $query->where(function ($q) use ($discountValue) {
                    $q->where('discount_percentage', $discountValue)
                      ->orWhere('discount_amount', $discountValue);
                });
            }
        }

        $query->orderBy('created_at', 'desc');
        $coupons = $query->paginate(15);
        $products = Product::all();
        $variants = Variant::all();
        return view('coupons.index', compact('coupons', 'products', 'variants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch all products to populate the product dropdown
        $products = Product::all();
        return view('coupons.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'code' => 'required|string|unique:coupons,code',
            'discount_type' => 'required|in:percentage,amount',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,id',
            'variant_id' => 'nullable|exists:variants,id',
        ];

        // Add conditional validation based on discount_type
        if ($request->input('discount_type') === 'percentage') {
            $rules['discount_percentage'] = 'required|numeric|min:0|max:100';
            $rules['discount_amount'] = 'nullable|numeric|min:0';
        } else {
            $rules['discount_amount'] = 'required|numeric|min:0';
            $rules['discount_percentage'] = 'nullable|numeric|min:0|max:100';
        }

        $validatedData = $request->validate($rules);

        // Prepare coupon data
        $couponData = [
            'code' => $validatedData['code'] ?: strtoupper(Str::random(10)),
            'discount_type' => $validatedData['discount_type'],
            'description' => $validatedData['description'] ?? null,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'active' => 1,
            'product_id' => $validatedData['product_id'] ?? null,
            'variant_id' => $validatedData['variant_id'] ?? null,
        ];

        // Assign the correct discount value based on discount_type
        if ($validatedData['discount_type'] === 'percentage') {
            $couponData['discount_percentage'] = $validatedData['discount_percentage'];
            $couponData['discount_amount'] = null;
        } else {
            $couponData['discount_amount'] = $validatedData['discount_amount'];
            $couponData['discount_percentage'] = null;
        }

        // Create the coupon
        Coupon::create($couponData);

        return redirect()->route('coupons.index')->with('success', 'تم إنشاء الكوبون بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        if ($request->wantsJson()) {
            return response()->json(['coupon' => $coupon], 200);
        }
        return view('coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        // Fetch all products to populate the product dropdown
        $products = Product::all();

        return view('coupons.edit', compact('coupon', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        // Define validation rules
        $rules = [
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:percentage,amount',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,id',
            'variant_id' => 'nullable|exists:variants,id',
        ];

        // Add conditional validation based on discount_type
        if ($request->input('discount_type') === 'percentage') {
            $rules['discount_percentage'] = 'required|numeric|min:0|max:100';
            $rules['discount_amount'] = 'nullable|numeric|min:0';
        } else {
            $rules['discount_amount'] = 'required|numeric|min:0';
            $rules['discount_percentage'] = 'nullable|numeric|min:0|max:100';
        }

        $validatedData = $request->validate($rules);

        // Prepare coupon data
        $couponData = [
            'code' => $validatedData['code'],
            'discount_type' => $validatedData['discount_type'],
            'description' => $validatedData['description'] ?? null,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'active' => 1,
            'product_id' => $validatedData['product_id'] ?? null,
            'variant_id' => $validatedData['variant_id'] ?? null,
        ];

        // Assign the correct discount value based on discount_type
        if ($validatedData['discount_type'] === 'percentage') {
            $couponData['discount_percentage'] = $validatedData['discount_percentage'];
            $couponData['discount_amount'] = null;
        } else {
            $couponData['discount_amount'] = $validatedData['discount_amount'];
            $couponData['discount_percentage'] = null;
        }

        // Update the coupon
        $coupon->update($couponData);

        return redirect()->route('coupons.index')->with('success', 'تم تحديث الكوبون بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        // Check if the coupon has been used in any orders
        if ($coupon->orders()->count()) {
            return redirect()->back()->withErrors(['لا يمكنك حذف كوبون تم استخدامه من قبل']);
        }

        // Delete the coupon
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'تم حذف الكوبون بنجاح');
    }

    /**
     * Fetch variants for a given product via AJAX.
     */
    public function getVariants(Request $request, $productId)
    {
        // Validate that the product exists
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        // Fetch variants associated with the product
        $variants = $product->variants()->select('id', 'name')->get();

        return response()->json(['variants' => $variants], 200);
    }


    public function toggle(Coupon $coupon)
    {
        $coupon->active = !$coupon->active;
        $coupon->save();

        return redirect()->route('coupons.index')->with('success', 'تم تغيير حالة الكوبون بنجاح');
    }
}
