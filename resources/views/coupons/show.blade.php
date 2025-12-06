@extends('layouts.app')

@section('title', 'عرض تفاصيل الكوبون')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-ticket-alt"></i> تفاصيل الكوبون
                </div>
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-code"></i> {{ $coupon->code }}
                        @if($coupon->active)
                            <span class="badge badge-success float-right">نشط</span>
                        @else
                            <span class="badge badge-danger float-right">غير نشط</span>
                        @endif
                    </h5>
                    <p class="card-text"><strong><i class="fas fa-percentage"></i> نسبة الخصم:</strong> 
                        @if($coupon->discount_type === 'percentage')
                            {{ $coupon->discount_percentage }}%
                        @else
                            {{ number_format($coupon->discount_amount, 2) }}$
                        @endif
                    </p>
                    <p class="card-text"><strong><i class="fas fa-info-circle"></i> الوصف:</strong> {{ $coupon->description }}</p>
                    <p class="card-text"><strong><i class="fas fa-calendar-alt"></i> تاريخ البداية:</strong> {{ $coupon->start_date }}</p>
                    <p class="card-text"><strong><i class="fas fa-calendar-alt"></i> تاريخ النهاية:</strong> {{ $coupon->end_date }}</p>

                    <h5 class="mt-4"><i class="fas fa-shopping-cart"></i> الطلبات المتعلقة بالكوبون</h5>
                    @if($coupon->orders->isEmpty())
                        <p class="card-text">لا توجد طلبات متعلقة بهذا الكوبون.</p>
                    @else
                        <ul class="list-group mb-3">
                            @foreach($coupon->orders as $order)
                                <li class="list-group-item">
                                    <strong>طلب #{{ $order->id }}:</strong> {{ $order->customer->name }} - {{ $order->total_amount }} {{ $order->paymentMethod->currency->symbol }}
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm float-right"><i class="fas fa-eye"></i> عرض الطلب</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-primary mt-3"><i class="fas fa-edit"></i> تعديل</a>
                    <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mt-3" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الكوبون؟')"><i class="fas fa-trash-alt"></i> حذف</button>
                    </form>
                    <a href="{{ route('coupons.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> رجوع إلى القائمة</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .card-header {
        background-color: #007bff;
        color: white;
    }
    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .btn-primary, .btn-danger, .btn-secondary {
        margin-right: 10px;
    }
    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
