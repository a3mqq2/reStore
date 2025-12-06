<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * عرض قائمة الأسئلة الشائعة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // البحث عن الأسئلة الشائعة بناءً على كلمة البحث إن وجدت
        $query = Faq::query();


        // الترتيب بناءً على عمود 'order'
        $query->orderBy('order', 'asc');

        // إذا كان هناك بحث، قم بتطبيق فلترة البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        // الحصول على النتائج
        $faqs = $query->paginate();

        // إرجاع العرض مع البيانات
        return view('faqs.index', compact('faqs'));
    }

    /**
     * عرض نموذج إنشاء سؤال شائع جديد.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('faqs.create');
    }

    /**
     * حفظ سؤال شائع جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer',
        ]);

        Faq::create($data);

        return redirect()->route('faqs.index')->with('success', 'تم إنشاء السؤال الشائع بنجاح.');
    }


    /**
     * عرض تفاصيل سؤال شائع معين.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\View\View
     */
    public function show(Faq $faq)
    {
        return view('faqs.show', compact('faq'));
    }

    /**
     * عرض نموذج تعديل سؤال شائع معين.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\View\View
     */
    public function edit(Faq $faq)
    {
        return view('faqs.edit', compact('faq'));
    }

    /**
     * تحديث سؤال شائع معين في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Faq $faq)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer',
            'is_active'=> 'nullable|boolean',
        ]);

        // تحديث البيانات في السجل المحدد
        $faq->update([
            'question' => $request->question,
            'answer'   => $request->answer,
            'order'    => $request->order ?? $faq->order,
            'is_active'=> $request->has('is_active') ? true : false,
        ]);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('faqs.index')->with('success', 'تم تحديث السؤال الشائع بنجاح.');
    }

    /**
     * حذف سؤال شائع معين من قاعدة البيانات.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Faq $faq)
    {
        // حذف السجل المحدد
        $faq->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('faqs.index')->with('success', 'تم حذف السؤال الشائع بنجاح.');
    }
}
