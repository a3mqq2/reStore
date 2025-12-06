@extends('layouts.app')

@section('title', 'إضافة خصم جديد')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-plus"></i> إضافة خصم جديد
        </div>
        <div class="card-body">
            <form action="{{ route('discounts.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="product_id" class="form-label">المنتج</label>
                    <select name="product_id" id="product_id" class="form-control select2">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="discount_percentage" class="form-label">نسبة الخصم</label>
                    <input type="number" step="0.01" step="0.01" name="discount_percentage" id="discount_percentage" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">تاريخ البداية</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">تاريخ النهاية</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> حفظ</button>
            </form>
        </div>
    </div>
</div>
@endsection
