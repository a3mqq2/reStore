<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Content;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index() {
        $content = Content::first();
        $banners = Banner::all();
        return view('content.index', compact('content','banners'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
            'email' => 'nullable|email',
            'whatsapp' => 'nullable|string',
            'policy' => 'nullable|string',
            'returns' => 'nullable|string',
            'about' => 'nullable|string',
            'message' => 'nullable|string',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'libyana' => "nullable",
            'madar' => "nullable",
            'point_cost' => 'nullable|numeric|min:0',
            'point_cost_libyana' => 'nullable|numeric|min:0',
            'point_cost_almadar' => 'nullable|numeric|min:0',
            'point_cost_red' => 'nullable|numeric|min:0',
            'point_cost_vfcash' => 'nullable|numeric|min:0',
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string',
            'dollar_buy_rate' => 'nullable|numeric|min:0',
            'dollar_sell_rate' => 'nullable|numeric|min:0',
            'smileone_point_usd' => 'nullable|numeric|min:0',
        ]);

        // تحديث المحتوى
        $content = Content::first();

        $data = $request->only(['facebook', 'instagram', 'email', 'whatsapp', 'telegram', 'policy', 'returns', 'about', 'message', 'libyana', 'madar', 'point_cost', 'point_cost_libyana', 'point_cost_almadar', 'point_cost_red', 'point_cost_vfcash', 'maintenance_message', 'dollar_buy_rate', 'dollar_sell_rate', 'smileone_point_usd']);

        // معالجة checkbox الصيانة - إذا لم يكن موجود في الطلب، يعني أنه غير محدد
        $data['maintenance_mode'] = $request->has('maintenance_mode') ? true : false;

        $content->update($data);

        return redirect()->back()->with('success', 'تم تحديث المحتوى بنجاح.');
    }

    public function store_banner(Request $request) {
        $request->validate([
            'banner_image' => 'required|max:2048',
            'banner_link' => 'required|max:2048',
        ]);

        $path = $request->file('banner_image')->store('banners', 'public');

        Banner::create([
            'image' => $path,
            'link' => $request->input('banner_link'), // Save the link
        ]);

        return redirect()->back()->with('banner_success', 'تم رفع البنر بنجاح.');
    }

    public function destroy(Banner $banner) {
        Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return redirect()->back()->with('banner_success', 'تم حذف البنر بنجاح.');
    }
}
