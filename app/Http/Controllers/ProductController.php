<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Customer;
use App\Models\ListItem;
use App\Models\Requirement;
use App\Models\VariantPrice;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index(Request $request)
{
    $query = Product::with(['category', 'variants']);
    
    if ($request->name) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    // If the request expects a JSON response
    if ($request->wantsJson()) {
        $products = Product::with([
            'variants', 
            'requirements', 
            'requirements.listItems', 
            'discount', 
            'variants.prices', 
            'variants.prices.paymentMethod', 
            'variants.prices.paymentMethod.currency'
        ])->get();

        // Add image URL to each product
        $products->each(function($product) {
            if ($product->image) {
                $product->image = Storage::url($product->image);
            }
        });

        $paymentMethods = PaymentMethod::with(['currency'])->where('active', 1)->get();
        $customers = Customer::all();

        return response(['products' => $products, 'paymentMethods' => $paymentMethods, 'customers' => $customers], 200);
    }

    // For non-JSON response, paginate products
    $products = $query->paginate(10);

    $categories = Category::all(); // To populate category filter options

    return view('products.index', compact('products', 'categories'));
}

     


    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',  // 2MB Max
            'requirements.*.name' => 'nullable',
            'requirements.*.type' => 'nullable',
            'requirements.*.listItems.*' => 'nullable',
        ]);
    
        $product = new Product([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);
    
        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }
    
        $product->save();
        
        if($request->category_id == 1 ) {
            foreach ($request->requirements as $req) {
                $requirement = new Requirement([
                    'name' => $req['name'],
                    'type' => $req['type'],
                    'product_id' => $product->id
                ]);
                $requirement->save();
        
                if ($req['type'] === 'list' && !empty($req['listItems'])) {
                    foreach ($req['listItems'] as $item) {
                        ListItem::create([
                            'requirement_id' => $requirement->id,
                            'item' => $item
                        ]);
                    }
                }
            }
        }
    
        return response()->json([
            'message' => 'تم انشاء المنتج بنجاح',
        ]);
    }

    /**
     * Display the specified product.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Request $request)
    {
        if ($request->wantsJson()) {
            $product = Product::with([
                'requirements',
                'requirements.listItems',
                'variants',
                'variants.prices' => function($query) {
                    $query->with('paymentMethod.currency')->orderBy('price', 'asc');
                },
                'discount'
            ])->find($product->id);

            // Sort prices by ascending order for each variant
            foreach ($product->variants as $variant) {
                $variant->prices = $variant->prices->sortBy('price');
            }

            return response()->json($product, 200);
        }
    
        $paymentMethods = PaymentMethod::where('active', 1)->get();
        return view('products.show', compact('product', 'paymentMethods'));
    }
    
    

    /**
     * Show the form for editing the specified product.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|max:2048',  // 2MB Max
        'requirements.*.name' => 'nullable',
        'requirements.*.type' => 'nullable',
        'requirements.*.listItems.*' => 'nullable',
    ]);

    $product = Product::findOrFail($id);

    $product->name = $request->name;
    $product->description = $request->description;
    $product->category_id = $request->category_id;

    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->image = $request->file('image')->store('products', 'public');
    }

    $product->save();

    if ($request->category_id == 1) {
        // Delete existing requirements and list items
        Requirement::where('product_id', $product->id)->delete();

        foreach ($request->requirements as $req) {
            $requirement = new Requirement([
                'name' => $req['name'],
                'type' => $req['type'],
                'product_id' => $product->id
            ]);
            $requirement->save();

            if ($req['type'] === 'list' && !empty($req['listItems'])) {
                foreach ($req['listItems'] as $item) {
                    ListItem::create([
                        'requirement_id' => $requirement->id,
                        'item' => $item
                    ]);
                }
            }
        }
    }

    return response()->json([
        'message' => 'تم تحديث المنتج بنجاح',
    ]);
}


    /**
     * Remove the specified product from storage.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
      
        $product->variants()->delete();
        $product->delete();
        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
    }


    public function editVariant(Variant $variant) {
        $paymentMethods = PaymentMethod::where('active',1)->get();
        return view('products.edit_variant', compact('variant','paymentMethods'));
    }

    public function addVariant(Request $request, $productId)
    {
        $request->validate([
            'variantName' => 'required|string|max:255',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0'
        ]);

        $product = Product::findOrFail($productId);

        $variant = new Variant();
        $variant->name = $request->variantName;
        $variant->product_id = $product->id;
        $variant->smileone_points = $request->smileone_points ?? 0;
        $variant->save();

        foreach ($request->prices as $paymentMethodId => $price) {
            $variantPrice = new VariantPrice();
            $variantPrice->variant_id = $variant->id;
            $variantPrice->payment_method_id = $paymentMethodId;
            $variantPrice->price = $price;
            $variantPrice->save();
        }

        return redirect()->route('products.show', $product->id)->with('success', 'تمت إضافة الفئة بنجاح');
    }


    public function updateVariant(Request $request, $variantId)
    {
        // التحقق من صحة البيانات الواردة في الطلب
        $request->validate([
            'variantName' => 'required|string|max:255',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0',
        ]);

        // البحث عن المتغير المطلوب وتأكيد وجوده
        $variant = Variant::findOrFail($variantId);
        $product = $variant->product;

        // تحديث اسم المتغير
        $variant->name = $request->variantName;
        $variant->smileone_points = $request->smileone_points ?? 0;

        // تحديث حقول الاسترداد
        $variant->redemption_value = $request->redemption_value ?? 0;
        $variant->is_redeemable = $request->has('is_redeemable');
        $variant->redemption_cost = $request->is_redeemable ? ($request->redemption_cost ?? 0) : null;

        $variant->save();

        // تحديث الأسعار المرتبطة بالمتغير
        foreach ($request->prices as $paymentMethodId => $price) {
            $variantPrice = VariantPrice::where('variant_id', $variant->id)
                                        ->where('payment_method_id', $paymentMethodId)
                                        ->first();

            if ($variantPrice) {
                // إذا كان السعر موجودًا بالفعل، يتم تحديثه
                $variantPrice->price = $price;
                $variantPrice->save();
            } else {
                // إذا لم يكن السعر موجودًا، يتم إنشاؤه
                $newVariantPrice = new VariantPrice();
                $newVariantPrice->variant_id = $variant->id;
                $newVariantPrice->payment_method_id = $paymentMethodId;
                $newVariantPrice->price = $price;
                $newVariantPrice->save();
            }
        }

        // إعادة التوجيه إلى صفحة المنتج مع رسالة نجاح
        return redirect()->route('products.show', $product->id)->with('success', 'تم تعديل الفئة بنجاح');
    }


    public function toggleStatus(Request $request, $id) {
        $product = Product::findOrFail($id);
        $product->active = !$product->active;
        $product->save();

        return redirect()->route('products.index')->with('success', 'تم تحديث حالة المنتج بنجاح.');
    }

    public function updateCashback(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update(['cashback' => $request->cashback]);

        return redirect()->route('products.show', $id)->with('success', 'تم تعديل نسبة Cashback بنجاح');
    }

    public function getVariants($productId)
    {
        $variants = Variant::where('product_id', $productId)->get();
        return response()->json(['variants' => $variants]);
    }


    public function search(Request $request)
    {
        // Validate the search input
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        // Get the search term from the request
        $searchTerm = $request->input('query');

        // Query the products table to find matching products
        $products = Product::where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
            ->take(10) // You can limit the number of results
            ->get();

        // Return the results as a JSON response
        return response()->json([
            'products' => $products,
        ]);
    }

    public function toggleActive(Request $request)
    {
        $variant = Variant::findOrFail($request->variant_id);
        $variant->is_active = $request->is_active;
        $variant->save();

        return response()->json([
            'success' => true,
            'message' => $variant->is_active ? 'تم تفعيل الفئة' : 'تم إلغاء تفعيل الفئة'
        ]);
    }

    public function updateSmileonePoints(Request $request)
    {
        $variant = Variant::findOrFail($request->variant_id);
        $variant->smileone_points = $request->smileone_points;
        $variant->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث نقاط SmileOne بنجاح'
        ]);
    }

    public function updateImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $product = Product::findOrFail($id);

        // Delete old image if exists
        if ($product->image && $product->image != '-') {
            Storage::disk('public')->delete($product->image);
        }

        // Store new image
        $product->image = $request->file('image')->store('products', 'public');
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصورة بنجاح',
            'image_url' => asset('storage/' . $product->image)
        ]);
    }

}
