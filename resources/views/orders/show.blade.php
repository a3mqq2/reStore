@extends('layouts.app')

@section('title', 'عرض الطلب')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('orders.index') }}" class="btn btn-primary mb-4">
                <i class="fas fa-arrow-left"></i> العودة إلى القائمة
            </a>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    تفاصيل الطلب
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">
                                <i class="fas fa-receipt"></i> طلب #{{ $order->id }}
                            </h5>
                            <p class="card-text">
                                <strong><i class="fas fa-user"></i> الزبون:</strong> {{ $order->customer->name }}
                            </p>
                            <p class="card-text">
                                <strong><i class="fas fa-calendar-alt"></i> تاريخ الطلب:</strong> {{ $order->order_date }}
                            </p>
                            <p class="card-text">
                                <strong><i class="fas fa-money-check"></i> طريقة الدفع:</strong> {{ $order->paymentMethod->name }} @if ($order->from_cashback)
                                    <span class="badge badge-success bg-success">نقاط استرداد</span>
                                @endif
                            </p>
                            <p class="card-text">
                                <strong><i class="fas fa-money-bill-wave"></i> الإجمالي:</strong> {{ $order->total_amount }} {{ $order->paymentMethod->currency->symbol }}
                            </p>
                            <p class="card-text">
                                <strong><i class="fas fa-percentage"></i> الإجمالي بعد الخصم:</strong> {{ $order->discounted_total }} {{ $order->paymentMethod->currency->symbol }}
                            </p>
                            <p class="card-text">
                                <strong><i class="fas fa-info-circle"></i> ملاحظات الدفع:</strong> {{ $order->payment_notes }}
                            </p>

                            @if ($order->payment_code)
                                <p class="card-text"><strong><i class="fas fa-receipt"></i> كود اللعبة:</strong> {{ $order->payment_code }}</p>
                            @endif

                            <p class="card-text">
                                <strong><i class="fas fa-info-circle"></i> الحالة:</strong> 
                                <span class="badge 
                                    @if($order->status == 'new') badge-secondary 
                                    @elseif($order->status == 'approved') badge-success 
                                    @elseif($order->status == 'canceled') badge-danger 
                                    @elseif($order->status == 'under_payment') badge-warning 
                                    @endif">
                                    @if($order->status == 'new')
                                        <i class="fas fa-hourglass-start"></i> جديد
                                    @elseif($order->status == 'approved')
                                        <i class="fas fa-check"></i> مكتمل
                                    @elseif($order->status == 'canceled')
                                        <i class="fas fa-times"></i> ملغي
                                    @elseif($order->status == 'under_payment')
                                        <i class="fas fa-credit-card"></i> قيد الشراء
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <h5 class="mt-4 mb-3">
                        <i class="fas fa-box"></i> تفاصيل المنتجات
                    </h5>
                    <div class="row">
                        @foreach ($order->products as $product)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-primary">
                                    @if ($product->product->image)
                                        <img src="{{ asset('storage/' . $product->product->image) }}" alt="{{ $product->name }}" class="card-img-top img-fluid">
                                    @else
                                        <div class="text-center p-5 bg-light border rounded">لا توجد صورة</div>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text"><strong>الكمية:</strong> {{ $product->quantity }}</p>
                                        <p class="card-text"><strong>الفئة:</strong> {{ $product->variant ? $product->variantObj->name : "" }}</p>
                                        <p class="card-text"><strong>السعر:</strong> {{ $product->price }} {{ $order->paymentMethod->currency->symbol }}</p>

                                        @if ($product->product->cashback)
                                            <span class="badge badge-success mb-3">
                                                نقاط الاسترداد: {{ $product->product->cashback }}
                                            </span>
                                        @endif

                                        @if ($product->product->category_id == 1)
                                            <p class="text-info font-weight-bold" style="font-weight: bold;">المتطلبات</p>
                                            @foreach ($product->requirements as $requirement)
                                                <p><strong>{{ $requirement->name }}:</strong> {{ $requirement->value }}</p>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Rating and Notes Section -->
                    <h5 class="mt-4 mb-3">
                        <i class="fas fa-star"></i> تقييم الطلب
                    </h5>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="card-text">
                                <strong><i class="fas fa-star"></i> التقييم:</strong> 
                                <span class="badge badge-warning">{{ $order->rating }} / 5</span>
                            </p>
                            <p class="card-text">
                                <strong><i class="fas fa-comment-dots"></i> ملاحظات التقييم:</strong> 
                                {{ $order->rating_notes ?? 'لا توجد ملاحظات.' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <!-- Existing buttons for editing, confirming, or canceling -->
                    @if ($order->status == "new")
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-info">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </form>
                    @endif

                    @if ($order->status == "new")
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal_{{ $order->id }}">
                            <i class="fas fa-check"></i> تأكيد
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#rejectModal_{{ $order->id }}">
                            <i class="fas fa-times"></i> إلغاء
                        </button>
                    @elseif ($order->status == "under_payment")
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal_{{ $order->id }}">
                            <i class="fas fa-check"></i> تأكيد الدفع
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if ($order->status == "new")
<div class="modal fade" id="approveModal_{{$order->id}}" tabindex="-1" aria-labelledby="approveModal_{{$order->id}}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModal_{{$order->id}}Label"> تم تاكيد الطلب </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="approveForm" method="POST" action="{{ route('orders.updateStatus') }}">
                    @csrf
                    <input type="hidden" name="status" value="under_payment">
                    <input type="hidden" name="order_id" value="{{$order->id}}" id="approveOrderId">
                    <div class="mb-3">
                        <label for="approveNotes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="approveNotes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">اعتماد</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectModal_{{$order->id}}" tabindex="-1" aria-labelledby="rejectModal_{{$order->id}}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModal_{{$order->id}}Label">إلغاء الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm" method="POST" action="{{ route('orders.updateStatus') }}">
                    @csrf
                    <input type="hidden" name="status" value="canceled">
                    <input type="hidden" name="order_id" value="{{$order->id}}" id="rejectOrderId">
                    <div class="mb-3">
                        <label for="rejectNotes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="rejectNotes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">إلغاء</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif


@endsection

@section('styles')
<style>
    /* Styling for the order details */
    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #343a40;
    }

    .card-text {
        margin-bottom: 10px;
        color: #495057;
    }

    .card-footer {
        background: #f8f9fa;
        text-align: right;
        border-top: 1px solid #e9ecef;
        padding: 10px 15px;
    }

    .btn-info, .btn-danger, .btn-warning, .btn-success {
        margin-right: 5px;
        border: none;
    }

    .btn-info:hover, .btn-danger:hover, .btn-warning:hover, .btn-success:hover {
        opacity: 0.85;
    }

    .img-fluid {
        max-height: 200px;
        object-fit: cover;
    }

    .text-primary {
        color: #007bff !important;
    }

    .badge-secondary {
        background-color: #6c757d;
    }
    .badge-success {
        background-color: #28a745;
    }
    .badge-danger {
        background-color: #dc3545;
    }
    .badge-warning {
        background-color: #ffc107;
    }

    /* Styling for rating */
    .badge-warning {
        background-color: #ffc107;
        color: black;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
