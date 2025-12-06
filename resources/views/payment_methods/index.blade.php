@extends('layouts.app')

@section('title', 'عرض طرق الدفع')

@section('content')
<div class="container mt-4">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('payment-methods.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إنشاء طريقة دفع جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- Filter form inside a card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن طرق الدفع
        </div>
        <div class="card-body">
            <form action="{{ route('payment-methods.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-9">
                        <input type="text" name="name" class="form-control mb-3" placeholder="ابحث بالاسم" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment methods display as cards -->
    <div class="row">
        @forelse ($paymentMethods as $paymentMethod)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-credit-card"></i> {{ $paymentMethod->name }}
                        </h5>
                        <div class="card-body">
                            <p>
                                <i class="fa fa-money-bill"></i> العملة : {{$paymentMethod->currency?->name}}
                            </p>
                            @if ($paymentMethod->id != 1)
                            <p>
                                <i class="fa fa-user"></i> ID\رقم الهاتف  : {{$paymentMethod->my_contact}}
                            </p>
                            @endif
                        </div>
                        <img src="{{ asset('images/payment_methods/' . $paymentMethod->image) }}" alt="{{ $paymentMethod->name }}" class="img-fluid mb-3 rounded shadow-sm">
                        <form action="{{ route('payment_methods.toggle_active', $paymentMethod->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $paymentMethod->active ? 'success' : 'secondary' }} btn-sm">
                                <i class="fas fa-toggle-{{ $paymentMethod->active ? 'on' : 'off' }}"></i> {{ $paymentMethod->active ? 'نشط' : 'غير نشط' }}
                            </button>
                        </form>
                    </div>
                    @if ($paymentMethod->id != 1)
                    <div class="card-footer">
                        <a href="{{ route('payment-methods.edit', $paymentMethod->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        @if (!$paymentMethod->orders->count())
                        <form action="{{ route('payment-methods.destroy', $paymentMethod->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا توجد طرق دفع متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $paymentMethods->appends(Request::all())->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-title {
        font-size: 1.25rem;
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

    .btn-info, .btn-danger, .btn-success, .btn-secondary {
        margin-right: 5px;
        border: none;
    }

    .btn-info:hover, .btn-danger:hover, .btn-success:hover, .btn-secondary:hover {
        opacity: 0.85;
    }

    .img-fluid {
        max-height: 150px;
        object-fit: cover;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
