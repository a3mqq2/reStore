@extends('layouts.app')

@section('title', 'الطلبات التي تحتوي على تقييم')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <h1 class="mb-4">الطلبات التي تحتوي على تقييم</h1>
            </div>
        </div>
    </div>

    <!-- Filter form inside a card (optional for future filtering if needed) -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن الطلبات
        </div>
        <div class="card-body">
            <form action="{{ route('orders.rated') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control mb-3" name="customer_name" placeholder="ابحث باسم الزبون" value="{{ request('customer_name') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control mb-3" name="order_date" placeholder="ابحث بتاريخ الطلب" value="{{ request('order_date') }}">
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
                        <p class="card-text"><strong><i class="fas fa-star"></i> التقييم:</strong> {{ $order->rating }}</p>
                        <p class="card-text"><strong><i class="fa fa-edit"></i> ملاحظات :</strong> {{ $order->rating_notes }}</p>
                        <p class="card-text"><strong><i class="fas fa-toggle-on"></i> إظهار في الصفحة الرئيسية:</strong> 
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-display" type="checkbox" 
                                    data-id="{{ $order->id }}" {{ $order->show_on_homepage ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $order->show_on_homepage ? 'مُظهر' : 'غير مُظهر' }}
                                </label>
                            </div>
                        </p>
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
</div>
@endsection

@section('styles')
<style>
    /* Styling for the orders with ratings */
    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #343a40;
    }

    .card-text {
        margin-bottom: 10px;
        color: #495057;
    }

    .form-check-label {
        font-weight: bold;
        margin-left: 10px;
    }

    .pagination {
        margin-top: 20px;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.toggle-display').on('change', function() {
            var orderId = $(this).data('id');
            var isChecked = $(this).is(':checked');
            
            $.ajax({
                url: '/toggle-display-on-homepage/' + orderId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                    }
                }
            });
        });
    });
</script>
@endsection
