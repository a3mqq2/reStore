@extends('layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-3 text-primary"><i class="fas fa-question-circle"></i> الأسئلة الشائعة</h1>
                <p class="text-muted">يمكنك هنا إدارة جميع الأسئلة الشائعة وإضافة أو تعديل أو حذف أي منها.</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('faqs.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> إضافة سؤال شائع جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن الأسئلة الشائعة
        </div>
        <div class="card-body">
            <form action="{{ route('faqs.index') }}" method="GET">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" name="search" placeholder="ابحث بسؤال أو إجابة" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="status" class="form-control">
                            <option value="">كل الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-5 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                        <a href="{{ route('faqs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- FAQs Display as Cards -->
    <div class="row">
        @forelse ($faqs as $faq)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="text-primary"><i class="fas fa-question-circle"></i> {{ $faq->question }}</h5>
                        <p>{{ Str::limit($faq->answer, 100, '...') }}</p>
                        <hr>
                        <p>
                            <i class="fas fa-sort-numeric-up"></i> <strong>ترتيب العرض:</strong> {{ $faq->order }}<br>
                            <i class="fas fa-toggle-on"></i> <strong>الحالة:</strong> 
                            @if($faq->is_active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </p>
                        <a href="{{ route('faqs.edit', $faq->id) }}" class="btn  btn-info">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('faqs.destroy', $faq->id) }}" class="d-inline" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال الشائع؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn  btn-danger">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-exclamation-circle"></i> لا توجد أسئلة شائعة متاحة حاليًا.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="pagination justify-content-center">
        {{ $faqs->appends(Request::all())->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-body h5 {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .card-body p {
        font-size: 0.95rem;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: none;
    }

    .btn-outline-info, .btn-outline-danger {
        width: 48%;
    }

    .pagination {
        margin-top: 20px;
    }

    .text-muted {
        font-size: 0.9rem;
    }
</style>
@endsection
