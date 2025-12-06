@extends('layouts.app')

@section('title', 'تعديل المتغير')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-light">تعديل المتغير</div>
    <div class="card-body">
        <form action="{{route('variants.update', $variant)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="variantName" class="form-label">اسم الفئة</label>
                <input type="text" class="form-control" id="variantName" value="{{$variant->name}}" name="variantName" required>
            </div>
            @foreach ($paymentMethods as $paymentMethod)
                <div class="mb-3">
                    <label for="price_{{ $paymentMethod->id }}" class="form-label">السعر ({{ $paymentMethod->name }})</label>
                    @php
                        $price = $variant->prices->where('payment_method_id', $paymentMethod->id)->first();
                    @endphp
                    @if (isset($price))
                    <input type="number" step="0.01" class="form-control" id="price_{{ $paymentMethod->id }}" name="prices[{{ $paymentMethod->id }}]" value="{{$price->price}}" required>
                    @else 
                    <input type="number" step="0.01" class="form-control" id="price_{{ $paymentMethod->id }}" name="prices[{{ $paymentMethod->id }}]" value="" required>
                    @endif
                </div>
            @endforeach

            @if ($variant->product->smileone_name)
            <div class="mb-3">
                <label for="smileone_points" class="form-label"><i class="fas fa-smile text-warning"></i> نقاط SmileOne</label>
                <input type="number" step="0.01" class="form-control" id="smileone_points" name="smileone_points" value="{{$variant->smileone_points??0}}">
            </div>
            @endif

            <hr class="my-4">
            <h5 class="text-primary mb-3"><i class="fas fa-exchange-alt"></i> إعدادات الاسترداد</h5>

            <div class="mb-3">
                <label for="redemption_value" class="form-label">قيمة الاسترداد عند الشراء</label>
                <input type="number" step="0.01" class="form-control" id="redemption_value" name="redemption_value" value="{{$variant->redemption_value??0}}">
                <small class="text-muted">القيمة التي يحصل عليها الزبون في رصيد الاسترداد عند شراء هذا المتغير</small>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_redeemable" name="is_redeemable" value="1" {{ $variant->is_redeemable ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_redeemable">يمكن استرداد هذا المتغير</label>
                </div>
            </div>

            <div class="mb-3" id="redemption_cost_container" style="{{ $variant->is_redeemable ? '' : 'display: none;' }}">
                <label for="redemption_cost" class="form-label">تكلفة الاسترداد</label>
                <input type="number" step="0.01" class="form-control" id="redemption_cost" name="redemption_cost" value="{{$variant->redemption_cost??0}}">
                <small class="text-muted">الحد الأدنى من رصيد الاسترداد المطلوب لاسترداد هذا المتغير</small>
            </div>

            <button class="btn btn-primary text-light">حفظ</button>
        </form>
    </div>
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
    document.getElementById('is_redeemable').addEventListener('change', function() {
        var container = document.getElementById('redemption_cost_container');
        if (this.checked) {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    });
</script>
@endsection
