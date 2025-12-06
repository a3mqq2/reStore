<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountCategoryController extends Controller
{
    public function index()
    {
        $categories = AccountCategory::withCount('accounts', 'availableAccounts')->get();
        return view('account_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('account_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'description', 'is_active']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('account_categories', 'public');
        }

        AccountCategory::create($data);

        return redirect()->route('account-categories.index')->with('success', 'تم إضافة التصنيف بنجاح');
    }

    public function edit(AccountCategory $accountCategory)
    {
        return view('account_categories.edit', compact('accountCategory'));
    }

    public function update(Request $request, AccountCategory $accountCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'description', 'is_active']);

        if ($request->hasFile('image')) {
            if ($accountCategory->image) {
                Storage::disk('public')->delete($accountCategory->image);
            }
            $data['image'] = $request->file('image')->store('account_categories', 'public');
        }

        $accountCategory->update($data);

        return redirect()->route('account-categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(AccountCategory $accountCategory)
    {
        if ($accountCategory->image) {
            Storage::disk('public')->delete($accountCategory->image);
        }

        $accountCategory->delete();

        return redirect()->route('account-categories.index')->with('success', 'تم حذف التصنيف بنجاح');
    }
}
