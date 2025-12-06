<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Customer;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::with(['category', 'customer']);

        if ($request->filled('category_id')) {
            $query->where('account_category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $accounts = $query->latest()->paginate(20);
        $categories = AccountCategory::where('is_active', true)->get();

        return view('accounts.index', compact('accounts', 'categories'));
    }

    public function create()
    {
        $categories = AccountCategory::where('is_active', true)->get();
        return view('accounts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_category_id' => 'required|exists:account_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Account::create($request->all());

        return redirect()->route('accounts.index')->with('success', 'تم إضافة الحساب بنجاح');
    }

    public function edit(Account $account)
    {
        $categories = AccountCategory::where('is_active', true)->get();
        return view('accounts.edit', compact('account', 'categories'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'account_category_id' => 'required|exists:account_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold',
            'notes' => 'nullable|string',
        ]);

        $account->update($request->all());

        return redirect()->route('accounts.index')->with('success', 'تم تحديث الحساب بنجاح');
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'تم حذف الحساب بنجاح');
    }

    public function sell(Request $request, Account $account)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        if ($account->status === 'sold') {
            return back()->with('error', 'هذا الحساب تم بيعه مسبقاً');
        }

        $account->markAsSold($request->customer_id);

        return redirect()->route('accounts.index')->with('success', 'تم بيع الحساب بنجاح');
    }
}
