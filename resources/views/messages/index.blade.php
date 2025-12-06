@extends('layouts.app')

@section('title', 'عرض الرسائل')

@section('content')
<div class="container">
    <!-- Filter form inside a card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن الرسائل
        </div>
        <div class="card-body">
            <form action="{{ route('messages.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <select class="form-control" name="sender">
                            <option value="">اختر المرسل</option>
                            <option value="Libyana" {{ request('sender') == 'Libyana' ? 'selected' : '' }}>ليبيانا</option>
                            <option value="Almadar" {{ request('sender') == 'Almadar' ? 'selected' : '' }}>المدار</option>
                            <option value="Unknown" {{ request('sender') == 'Unknown' ? 'selected' : '' }}>غير معروف</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select class="form-control" name="status">
                            <option value="">اختر حالة الرسالة</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>ناجحة</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>فاشلة</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages display as cards -->
    <div class="row">
        @forelse ($messages as $message)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-envelope"></i> رسالة #{{ $message->id }}
                        </h5>
                        <p class="card-text"><strong><i class="fas fa-user"></i> المرسل:</strong> {{ $message->sender }}</p>
                        <p class="card-text"><strong><i class="fas fa-phone"></i> رقم المصدر:</strong> {{ $message->source_number }}</p>
                        <p class="card-text">
                            <strong><i class="fas fa-align-left"></i> محتوى الرسالة:</strong>
                            <span class="message-content" id="message-content-{{ $message->id }}">{{ $message->message }}</span>
                        </p>
                        <p class="card-text">
                            <strong><i class="fa fa-user"></i></strong>
                            <span>{{$message->customer?->name}}</span>
                        </p>
                        <p class="card-text"><strong><i class="fas fa-clock"></i> التاريخ:</strong> {{ $message->timestamp }}</p>
                        <p class="card-text"><strong><i class="fas fa-info-circle"></i> الحالة:</strong>
                            <span class="badge {{ $message->status ? 'badge-success' : 'badge-danger' }}">
                                {{ $message->status ? 'ناجحة' : 'فاشلة' }}
                            </span>
                        </p>
                        <p class="card-text"><strong><i class="fas fa-coins"></i> القيمة:</strong> {{ $message->value }} د.ل</p>
                        @if($message->customer)
                            <p class="card-text"><strong><i class="fas fa-user-circle"></i> الزبون:</strong> {{ $message->customer->name }}</p>
                        @endif
                        <p class="card-text"><strong><i class="fas fa-tag"></i> النوع:</strong> {{ $message->type }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا توجد رسائل متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $messages->appends(Request::all())->links() }}
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
    .badge-danger {
        background-color: #dc3545;
    }

    .message-content {
        display: block;
        max-height: 3em;
        overflow: hidden;
        transition: max-height 0.3s;
    }

    .message-content.show {
        max-height: none;
    }
</style>
@endsection

@section('scripts')
<!-- تضمين Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<!-- تضمين jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function(){
        $('.toggle-message').click(function(){
            var target = $(this).data('target');
            $(target).toggleClass('show');
            var buttonText = $(target).hasClass('show') ? 'إخفاء' : 'عرض كامل';
            $(this).text(buttonText);
        });
    });
</script>
@endsection
