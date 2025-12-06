@extends('layouts.app')

@section('title', 'تعديل الفئة')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit"></i> تعديل الفئة
        </div>
        <div class="card-body">
            <form action="{{ route('product_categories.update', $productCategory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Category Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">اسم الفئة</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $productCategory->name }}" required>
                </div>

                <!-- Active Status -->
                <div class="mb-3">
                    <label for="active" class="form-label">نشط</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ $productCategory->active ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">تفعيل</label>
                    </div>
                </div>

                <!-- Current Image -->
                @if($productCategory->image)
                    <div class="mb-3">
                        <label class="form-label">الصورة الحالية:</label>
                        <div>
                            <img src="{{ Storage::url($productCategory->image) }}" alt="{{ $productCategory->name }}" class="img-fluid mb-3" width="150" height="150">
                        </div>
                    </div>
                @endif

                <!-- New Image Upload -->
                <div class="mb-3">
                    <label for="image" class="form-label">تغيير الصورة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">اختر صورة جديدة للفئة إذا كنت ترغب في استبدال الصورة الحالية.</small>
                </div>

                <!-- Multi-Select Products -->
                <div class="mb-3">
                    <label for="products" class="form-label">اختر المنتجات</label>
                    <select class="form-control select2" id="products" name="products[]" multiple="multiple" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">اضغط على Ctrl أو Cmd لتحديد منتجات متعددة.</small>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> حفظ التغييرات</button>
                <a href="{{ route('product_categories.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .form-label {
        font-weight: bold;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .img-fluid {
        max-height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for multi-select
        $('#products').select2({
            placeholder: 'اختر المنتجات',
            allowClear: true
        });
    });
</script>
@endsection
