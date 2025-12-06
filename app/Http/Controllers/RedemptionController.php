<?php

namespace App\Http\Controllers;

use App\Models\Redemption;
use Illuminate\Http\Request;
use App\Mail\RedemptionCompleted;
use Illuminate\Support\Facades\Mail;

class RedemptionController extends Controller
{


    public function complete($id)
    {
        $redemption = Redemption::findOrFail($id);

        if ($redemption->status == Redemption::STATUS_PENDING) {
            $redemption->status = Redemption::STATUS_COMPLETED;
            $redemption->save();

            Mail::to($redemption->customer->email)->send(new RedemptionCompleted($redemption));
        }

        return redirect()->back()->with('success', 'تم تغيير حالة الاسترداد إلى منفذ بنجاح.');
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Redemption::query();

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Apply date filter
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $redemptions = $query->paginate(10);

        return view('redemptions.index', compact('redemptions'));
    }

}
