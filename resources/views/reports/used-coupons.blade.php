@extends('layouts.app')

@section('title', 'تقرير الكوبونات المستخدمة')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header bg-primary text-light">
            تقرير الكوبونات المستخدمة خلال فترة
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الكوبون</th>
                        <th>إجمالي الاستخدامات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usedCoupons as $coupon)
                        <tr>
                            <td>{{ $coupon->coupon->code }}</td>
                            <td>{{ $coupon->total_uses }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
