@extends('layouts.app')

@section('title', 'إضافة فئة جديدة')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-plus"></i> إضافة فئة جديدة
        </div>
        <div class="card-body">
            <form action="{{ route('product_categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Category Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">اسم الفئة</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <!-- Active Status -->
                <div class="mb-3">
                    <label for="active" class="form-label">نشط</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" checked>
                        <label class="form-check-label" for="active">تفعيل</label>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mb-3">
                    <label for="image" class="form-label">صورة الفئة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">يرجى تحميل صورة للفئة.</small>
                </div>

                <!-- Multi-Select Products -->
                <div class="mb-3">
                    <label for="products" class="form-label">اختر المنتجات</label>
                    <select class="form-control select2" id="products" name="products[]" multiple="multiple" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">اضغط على Ctrl أو Cmd لتحديد منتجات متعددة.</small>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> حفظ</button>
                <a href="{{ route('product_categories.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-label {
        font-weight: bold;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@endsection
