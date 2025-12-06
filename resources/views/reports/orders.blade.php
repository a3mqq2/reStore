@extends('layouts.app')

@section('title', 'تقرير الطلبات')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header bg-primary text-light">
            تقرير الطلبات خلال فتره
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>اسم الزبون</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->customer->name }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>
                                <p class="card-text"><strong><i class="fas fa-info-circle"></i> الحالة:</strong> 
                                    <span class="badge 
                                        @if($order->status == 'new') badge-secondary  bg-secondary
                                        @elseif($order->status == 'approved') badge-success  bg-success
                                        @elseif($order->status == 'canceled') badge-danger  bg-danger
                                        @elseif($order->status == 'under_payment') badge-warning  bg-warning
                                        @endif">
                                        @if($order->status == 'new')
                                            جديد
                                        @elseif($order->status == 'approved')
                                            مكتمل
                                        @elseif($order->status == 'canceled')
                                            ملغي
                                        @elseif($order->status == 'under_payment')
                                            قيد الشراء
                                        @endif
                                    </span>
                                </p></td>
                            <td>{{ $order->total_amount }} {{ $order->paymentMethod->currency->symbol }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
