@extends('layouts.app')

@section('title', 'إدارة الفئات')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-success" href="{{route('product_categories.create')}}">
                    <i class="fas fa-plus"></i> إنشاء فئة جديدة
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse ($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <!-- Category Image -->
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="img-fluid mb-3" style="max-height: 150px; object-fit: cover;">
                        @else
                            <div class="text-muted mb-3" style="height: 150px; background: #f8f9fa; display: flex; justify-content: center; align-items: center;">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        @endif

                        <!-- Category Name -->
                        <h5 class="card-title text-primary">
                            <i class="fas fa-tag"></i> {{ $category->name }}
                        </h5>

                        <!-- Active Status -->
                        <p class="card-text">
                            <i class="fas fa-check-circle"></i> {{ $category->active ? 'نشط' : 'غير نشط' }}
                        </p>

                        <!-- Associated Products -->
                        <p class="card-text">
                            <i class="fas fa-boxes"></i> المنتجات:
                            @if($category->products->count())
                                @foreach($category->products as $product)
                                    <span class="badge bg-secondary">{{ $product->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">لا توجد منتجات</span>
                            @endif
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card-footer d-flex justify-content-between">
                        <a class="btn btn-info" href="{{route('product_categories.edit', $category)}}">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal-{{ $category->id }}">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted"><i class="fas fa-exclamation-circle"></i> لا توجد فئات متاحة</p>
            </div>
        @endforelse
    </div>
</div>

@foreach ($categories as $category)
    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCategoryModal-{{ $category->id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('product_categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCategoryModalLabel">حذف الفئة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        هل أنت متأكد أنك تريد حذف الفئة "{{ $category->name }}"؟
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">نعم، احذفها</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection

@section('styles')
<style>
    .card-body img {
        border-radius: 8px;
    }

    .card-footer {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }

    .btn {
        margin-right: 10px;
    }

    .badge {
        margin: 2px;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
