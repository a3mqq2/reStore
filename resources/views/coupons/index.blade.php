@extends('layouts.app')

@section('title', 'عرض الكوبونات')

@section('content')
<div class="container mt-4">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('coupons.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إنشاء كوبون جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Filter form inside a card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن الكوبونات
        </div>
        <div class="card-body">
            <form action="{{ route('coupons.index') }}" method="GET">
                <div class="row">
                    <!-- Search by Code -->
                    <div class="col-md-3">
                        <input type="text" class="form-control mb-3" name="code" placeholder="ابحث بالكود" value="{{ request('code') }}">
                    </div>
                    
                    <!-- Search by Discount Type -->
                    <div class="col-md-3">
                        <select name="discount_type" class="form-control mb-3">
                            <option value="">-- نوع الخصم --</option>
                            <option value="percentage" {{ request('discount_type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                            <option value="amount" {{ request('discount_type') == 'amount' ? 'selected' : '' }}>مبلغ ثابت</option>
                        </select>
                    </div>
                    
                    <!-- Search by Active Status -->
                    <div class="col-md-3">
                        <select name="active" class="form-control mb-3">
                            <option value="">-- الحالة --</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    
                    <!-- Search by Product -->
                    <div class="col-md-3">
                        <select name="product_id" class="form-control mb-3">
                            <option value="">-- اختر منتج --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Search by Discount Value -->
                    <div class="col-md-3">
                        <input type="number" step="0.01" class="form-control mb-3" name="discount_value" placeholder="ابحث بقيمة الخصم" value="{{ request('discount_value') }}" min="0">
                    </div>
                    
                    <!-- Search by Variant (Optional) -->
                    <div class="col-md-3">
                        <select name="variant_id" class="form-control mb-3">
                            <option value="">-- اختر نوع فرعي --</option>
                            @foreach ($variants as $variant)
                                <option value="{{ $variant->id }}" {{ request('variant_id') == $variant->id ? 'selected' : '' }}>
                                    {{ $variant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mb-3">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                    
                    <!-- Reset Button -->
                    <div class="col-md-3">
                        <a href="{{ route('coupons.index') }}" class="btn btn-secondary mb-3">
                            <i class="fas fa-sync-alt"></i> إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Coupons display as cards -->
    <div class="row">
        @forelse ($coupons as $coupon)
            <div class="col-md-4 mb-4">
                <div class="card h-100 {{ $coupon->active ? 'border-success' : 'border-danger' }}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-ticket-alt"></i> {{ $coupon->code }}
                            @if($coupon->active)
                                <span class="badge badge-success">نشط</span>
                            @else
                                <span class="badge badge-danger">غير نشط</span>
                            @endif
                        </h5>
                        <p class="card-text">{{ $coupon->description }}</p>
                        
                        <!-- Display Discount Type and Value -->
                        <p class="card-text">
                            <small class="text-muted">
                                @if($coupon->discount_type === 'percentage')
                                    <i class="fas fa-percentage"></i> {{ $coupon->discount_percentage }}%
                                @elseif($coupon->discount_type === 'amount')
                                    <i class="fas fa-dollar-sign"></i> {{ number_format($coupon->discount_amount, 2) }}$
                                @endif
                            </small>
                        </p>
                        
                        <!-- Display Product and Variant if available -->
                        @if($coupon->product)
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-box-open"></i> منتج: {{ $coupon->product->name }}
                                </small>
                            </p>
                        @endif
                        
                        @if($coupon->variant)
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-ticket"></i> نوع فرعي: {{ $coupon->variant->name }}
                                </small>
                            </p>
                        @endif
                        
                        <!-- Display Dates -->
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i> تاريخ البداية: {{ \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d') }}
                            </small>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i> تاريخ النهاية: {{ \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d') }}
                            </small>
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('coupons.show', $coupon->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                        <!-- Toggle Active Status -->
                        <form action="{{ route('coupons.toggle', $coupon->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('هل تريد تغيير حالة الكوبون؟')">
                                <i class="fas fa-toggle-on"></i> {{ $coupon->active ? 'غير نشط' : 'نشط' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا يوجد كوبونات متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $coupons->appends(Request::all())->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling for the coupon details */
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

    .btn-info, .btn-danger, .btn-primary, .btn-warning {
        margin-right: 5px;
        border: none;
    }

    .btn-info:hover, .btn-danger:hover, .btn-primary:hover, .btn-warning:hover {
        opacity: 0.85;
    }

    .text-muted {
        color: #6c757d !important;
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

    .border-success {
        border-width: 2px !important;
    }

    .border-danger {
        border-width: 2px !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
