@extends('layouts.app')

@section('title', 'عرض المدن')

@section('content')
<div class="container mt-4">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('cities.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إنشاء مدينة جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- Filter form inside a card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن المدن
        </div>
        <div class="card-body">
            <form action="{{ route('cities.index') }}" method="GET">
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

    <!-- Cities display as cards -->
    <div class="row">
        @forelse ($cities as $city)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-city"></i> {{ $city->name }}
                        </h5>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('cities.edit', $city->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('cities.destroy', $city->id) }}" method="POST" style="display: inline;">
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
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا توجد مدن متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $cities->appends(Request::all())->links() }}
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

    .text-primary {
        color: #007bff !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
