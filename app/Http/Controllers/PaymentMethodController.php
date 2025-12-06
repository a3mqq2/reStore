<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::paginate();
        return view('payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currencies = Currency::all();
        return view('payment_methods.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/payment_methods'), $imageName);
        }
    
        PaymentMethod::create([
            'name' => $request->name,
            'image' => $imageName,
            "currency_id" => $request->currency_id,
            'my_contact' => $request->my_contact,
        ]);
    
        return redirect()->route('payment-methods.index')->with('success', 'تم إضافة طريقة الدفع بنجاح.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        $currencies = Currency::all();
        return view('payment_methods.edit', compact('paymentMethod','currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            "currency_id" => "required",
            'my_contact' => "required",
        ]);
    
        // تحديث اسم طريقة الدفع
        $paymentMethod->name = $request->name;
    
        // إذا تم تحميل صورة جديدة، احفظها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($paymentMethod->image && file_exists(public_path('images/payment_methods/'.$paymentMethod->image))) {
                unlink(public_path('images/payment_methods/'.$paymentMethod->image));
            }
    
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/payment_methods'), $imageName);
    
            // تحديث اسم الصورة في قاعدة البيانات
            $paymentMethod->image = $imageName;
        }
        
        $paymentMethod->currency_id = $request->currency_id;
        $paymentMethod->my_contact= $request->my_contact;
        $paymentMethod->save();
    
        return redirect()->route('payment-methods.index')->with('success', 'تم تحديث طريقة الدفع بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        //
    }

    public function toggleActive(PaymentMethod $paymentMethod)
    {
        $paymentMethod->active = !$paymentMethod->active;
        $paymentMethod->save();

        return redirect()->route('payment-methods.index')->with('success', 'تم تحديث حالة النشاط بنجاح.');
    }
}
