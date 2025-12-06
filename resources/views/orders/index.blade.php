@extends('layouts.app')

@section('title', 'عرض الطلبات')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('orders.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إنشاء طلب جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Filter form inside a card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن الطلبات
        </div>
        <div class="card-body">
            <form action="{{ route('orders.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control mb-3" name="customer_name" placeholder="ابحث باسم الزبون" value="{{ request('customer_name') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control mb-3" name="order_date" placeholder="ابحث بتاريخ الطلب" value="{{ request('order_date') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control mb-3" name="status">
                            <option value="">اختر حالة الطلب</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>مكتمل</option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغي</option>
                            <option value="under_payment" {{ request('status') == 'under_payment' ? 'selected' : '' }}>قيد الشراء</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control mb-3" name="payment_method_id">
                            <option value="">اختر طريقة الدفع</option>
                            @foreach ($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}" {{ request('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                    {{ $paymentMethod->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" step="0.01" class="form-control mb-3" name="min_total_amount" placeholder="الحد الأدنى للإجمالي" value="{{ request('min_total_amount') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" step="0.01" class="form-control mb-3" name="max_total_amount" placeholder="الحد الأقصى للإجمالي" value="{{ request('max_total_amount') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders display as cards -->
    <div class="row">
        @forelse ($orders as $order)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-receipt"></i> طلب #{{ $order->id }}
                        </h5>
                        <p class="card-text"><strong><i class="fas fa-user"></i> الزبون:</strong> {{ $order->customer->name }}</p>
                        <p class="card-text"><strong><i class="fas fa-calendar-alt"></i> تاريخ الطلب:</strong> {{ $order->order_date }}</p>
                        <p class="card-text"><strong><i class="fas fa-money-bill"></i> الإجمالي:</strong> {{ $order->total_amount }} {{ $order->paymentMethod->currency->symbol }}</p>
                        <p class="card-text"><strong><i class="fas fa-percentage"></i> الإجمالي بعد الخصم:</strong> {{ $order->discounted_total }} {{ $order->paymentMethod->currency->symbol }}</p>
                        <p class="card-text"><strong><i class="fas fa-info-circle"></i> الحالة:</strong> 
                            <span class="badge 
                                @if($order->status == 'new') badge-secondary 
                                @elseif($order->status == 'approved') badge-success 
                                @elseif($order->status == 'canceled') badge-danger 
                                @elseif($order->status == 'under_payment') badge-warning 
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
                        </p>

                        @if ($order->payment_code)
                            <p class="card-text"><strong><i class="fas fa-receipt"></i> كود اللعبة :</strong> {{ $order->payment_code }}</p>
                        @endif

                    </div>
                    <div class="card-footer">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                       
                        
                        @if ($order->status == "new")
                         <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal_{{$order->id}}" data-order-id="{{ $order->id }}">
                                <i class="fas fa-check"></i> تاكيد 
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal_{{$order->id}}" data-order-id="{{ $order->id }}">
                                <i class="fas fa-times"></i> إلغاء
                            </button>
                        @endif

                        @if ($order->status == "under_payment")
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#paymentCodeModal_{{$order->id}}" data-order-id="{{ $order->id }}">
                                <i class="fas fa-check"></i>  تاكيد  الدفع
                            </button>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا يوجد طلبات متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $orders->appends(Request::all())->links() }}
    </div>

    @foreach ($orders as $order)
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

    @if ($order->status == "under_payment")
    @php
        $hasCategory2 = $order->products->contains('category_id', 2);
    @endphp

    <div class="modal fade" id="paymentCodeModal_{{$order->id}}" tabindex="-1" aria-labelledby="paymentCodeModal_{{$order->id}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentCodeModal_{{$order->id}}Label"> تاكيد الدفع </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentCodeForm" method="POST" action="{{ route('orders.updateStatus') }}">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <input type="hidden" name="order_id" value="{{$order->id}}" id="paymentCodeOrderId">

                        @php
                            $is_payment_code = null;
                            $products = $order->products;
                            foreach ($products as  $product) {
                                $pr = $product->product;
                                if($pr->category_id == 2) {
                                    $is_payment_code = true;  
                                }
                            }
                        @endphp

                        @if ($is_payment_code && $order->status == "under_payment")
                        <div class="mb-3">
                            <label for="paymentCode" class="form-label">كود الدفع</label>
                            <input type="text" name="payment_code" id="" class="form-control">
                        </div>
                        @endif



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
    @endif
    @endforeach
</div>
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
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    const approveModal = document.getElementById('approveModal');
    approveModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const orderId = button.getAttribute('data-order-id');
        document.getElementById('approveOrderId').value = orderId;
    });

    const rejectModal = document.getElementById('rejectModal');
    rejectModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const orderId = button.getAttribute('data-order-id');
        document.getElementById('rejectOrderId').value = orderId;
    });

    const paymentCodeModal = document.getElementById('paymentCodeModal');
    paymentCodeModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const orderId = button.getAttribute('data-order-id');
        document.getElementById('paymentCodeOrderId').value = orderId;
    });
</script>
@endsection
