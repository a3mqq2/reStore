@extends('layouts.app')

@section('title', 'تعديل تصنيف الحسابات')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit"></i> تعديل تصنيف الحسابات
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('account-categories.update', $accountCategory) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">اسم التصنيف <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $accountCategory->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $accountCategory->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">صورة التصنيف</label>
                    @if($accountCategory->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($accountCategory->image) }}" alt="{{ $accountCategory->name }}" style="max-height: 150px;" class="img-thumbnail">
                            <p class="text-muted small mt-1">الصورة الحالية</p>
                        </div>
                    @endif
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">اترك الحقل فارغاً للإبقاء على الصورة الحالية</small>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $accountCategory->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">تفعيل التصنيف</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> حفظ التعديلات
                    </button>
                    <a href="{{ route('account-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
