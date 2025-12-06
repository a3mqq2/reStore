@extends('layouts.app')

@section('title', 'إدارة الخصومات')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('discounts.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إضافة خصم جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Discounts display as cards -->
    <div class="row">
        @forelse ($discounts as $discount)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-tag"></i> خصم على {{ $discount->product->name }}
                        </h5>
                        <p class="card-text"><strong><i class="fas fa-percentage"></i> نسبة الخصم:</strong> {{ $discount->discount_percentage }}%</p>
                        <p class="card-text"><strong><i class="fas fa-calendar-alt"></i> تاريخ البداية:</strong> {{ $discount->start_date }}</p>
                        <p class="card-text"><strong><i class="fas fa-calendar-alt"></i> تاريخ النهاية:</strong> {{ $discount->end_date }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('discounts.edit', $discount->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا يوجد خصومات متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $discounts->appends(Request::all())->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling for the discount details */
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

    .btn-primary, .btn-danger {
        margin-right: 5px;
        border: none;
    }

    .btn-primary:hover, .btn-danger:hover {
        opacity: 0.85;
    }

    .text-primary {
        color: #007bff !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
