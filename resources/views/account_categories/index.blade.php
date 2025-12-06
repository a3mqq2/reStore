@extends('layouts.app')

@section('title', 'إدارة تصنيفات الحسابات')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-success" href="{{route('account-categories.create')}}">
                    <i class="fas fa-plus"></i> إضافة تصنيف جديد
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @forelse ($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="img-fluid mb-3" style="max-height: 150px; object-fit: cover; width: 100%;">
                        @else
                            <div class="text-muted mb-3" style="height: 150px; background: #f8f9fa; display: flex; justify-content: center; align-items: center;">
                                <i class="fas fa-user-circle fa-3x"></i>
                            </div>
                        @endif

                        <h5 class="card-title text-primary">
                            <i class="fas fa-layer-group"></i> {{ $category->name }}
                        </h5>

                        @if($category->description)
                            <p class="card-text text-muted small">
                                {{ Str::limit($category->description, 100) }}
                            </p>
                        @endif

                        <p class="card-text">
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                <i class="fas fa-{{ $category->is_active ? 'check' : 'times' }}-circle"></i>
                                {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </p>

                        <div class="row text-center mt-3">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h6 class="text-primary mb-0">{{ $category->accounts_count }}</h6>
                                    <small class="text-muted">إجمالي الحسابات</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h6 class="text-success mb-0">{{ $category->available_accounts_count }}</h6>
                                    <small class="text-muted">المتاحة</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a class="btn btn-info btn-sm" href="{{route('account-categories.edit', $category)}}">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $category->id }}">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> لا توجد تصنيفات متاحة
                </div>
            </div>
        @endforelse
    </div>
</div>

@foreach ($categories as $category)
    <div class="modal fade" id="deleteModal-{{ $category->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('account-categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">حذف التصنيف</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من حذف التصنيف "<strong>{{ $category->name }}</strong>"؟</p>
                        @if($category->accounts_count > 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                تحذير: يحتوي هذا التصنيف على {{ $category->accounts_count }} حساب/حسابات. سيتم حذف جميع الحسابات المرتبطة به.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">نعم، احذف</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
