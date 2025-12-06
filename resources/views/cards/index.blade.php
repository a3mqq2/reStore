@extends('layouts.app')

@section('title', 'عرض البطاقات')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('cards.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إنشاء بطاقة جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- Filter form inside a card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن البطاقات
        </div>
        <div class="card-body">
            <form action="{{ route('cards.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-control mb-3" name="status">
                            <option value="">اختر حالة البطاقة</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد</option>
                            <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>مستعمل</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cards display as cards -->
    <div class="row">
        @forelse ($cards as $card)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-ticket-alt"></i> بطاقة #{{ $card->id }}
                        </h5>
                        <p class="card-text">
                            <strong><i class="fas fa-key"></i> الرقم السري:</strong> 
                            <span class="secret-number" id="secret-number-{{ $card->id }}">{{ $card->secret_number }}</span>
                            <button class="btn btn-sm btn-outline-primary toggle-secret" data-target="#secret-number-{{ $card->id }}">إظهار</button>
                        </p>
                        <p class="card-text"><strong><i class="fas fa-barcode"></i> الرقم التسلسلي:</strong> {{ $card->serial_number }}</p>
                        <p class="card-text"><strong><i class="fas fa-dollar-sign"></i> المبلغ:</strong> {{ $card->amount }} {{ $card->currency_symbol ?? 'د.ل' }}</p>
                        @if($card->customer)
                            <p class="card-text"><strong><i class="fas fa-user"></i> الزبون:</strong> {{ $card->customer->name }}</p>
                        @endif
                        <p class="card-text"><strong><i class="fas fa-info-circle"></i> الحالة:</strong> 
                            <span class="badge 
                                @if($card->status == 'new') badge-secondary 
                                @elseif($card->status == 'used') badge-success 
                                @endif">
                                @if($card->status == 'new')
                                    جديد
                                @elseif($card->status == 'used')
                                    مستعمل
                                @endif
                            </span>
                        </p>
                    </div>
                    @if($card->status == 'new')
                        <div class="card-footer">
                            <a href="{{ route('cards.edit', $card->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            <form action="{{ route('cards.destroy', $card->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                            <a href="{{ route('cards.print', $card->id) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-print"></i> طباعة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا توجد بطاقات متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $cards->appends(Request::all())->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling for the card details */
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

    .btn-info, .btn-danger, .btn-warning, .btn-success {
        margin-right: 5px;
        border: none;
    }

    .btn-info:hover, .btn-danger:hover, .btn-warning:hover, .btn-success:hover {
        opacity: 0.85;
    }

    .img-fluid {
        max-height: 200px;
        object-fit: cover;
    }

    .text-primary {
        color: #007bff !important;
    }

    .badge-secondary {
        background-color: #6c757d;
    }
    .badge-success {
        background-color: #28a745;
    }

    .secret-number {
        filter: blur(8px);
        transition: filter 0.3s;
    }

    .secret-number.show {
        filter: none;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function(){
        $('.toggle-secret').click(function(){
            var target = $(this).data('target');
            $(target).toggleClass('show');
            var buttonText = $(target).hasClass('show') ? 'إخفاء' : 'إظهار';
            $(this).text(buttonText);
        });
    });
</script>
@endsection
