@extends('layouts.app')

@section('title', 'عرض المنتجات')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('products.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إنشاء منتج جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Filter form inside a card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن المنتجات
        </div>
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control mb-3" name="name" placeholder="ابحث بالاسم" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-control mb-3" name="category_id">
                            <option value="">اختر فئة</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products display as cards -->
    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($product->image)
                        <div style="position: relative;">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @if ($product->min_cashback)
                                <div style="position: absolute; bottom: 10px; left: 10px; background-color: rgba(0, 0, 0, 0.6); color: white; padding: 5px;">
                                    يبدأ الاسترداد من {{ $product->min_cashback }} 
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center p-5 bg-light border rounded">لا توجد صورة</div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-box"></i> {{ $product->name }}
                            @if ($product->discount && $product->discount->isActive())
                                <span class="badge badge-warning"><i class="fas fa-percentage"></i> خصم {{ $product->discount->discount_percentage }}%</span>
                            @endif
                        </h5>
                        <p class="card-text"><i class="fas fa-info-circle"></i> {{ $product->description }}</p>
                        <p class="card-text"><small class="text-muted"><i class="fas fa-tags"></i> {{ $product->category->name }}</small></p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                        <form action="{{ route('products.toggle', $product->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $product->active ? 'btn-secondary' : 'btn-success' }}">
                                <i class="fas {{ $product->active ? 'fa-eye-slash' : 'fa-eye' }}"></i> {{ $product->active ? 'إخفاء' : 'تفعيل' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا يوجد منتجات متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $products->appends(Request::all())->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling for the product details */
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

    .btn-info, .btn-danger {
        margin-right: 5px;
        border: none;
    }

    .btn-info:hover, .btn-danger:hover {
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
@endsection
