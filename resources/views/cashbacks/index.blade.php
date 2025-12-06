@extends('layouts.app')

@section('title', 'إدارة الاستردادات النقدية')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('cashbacks.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إضافة استرداد نقدي جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Cashbacks display as cards -->
    <div class="row">
        @forelse ($cashbacks as $cashback)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <!-- عرض الصورة كأيقونة صغيرة -->
                            @if ($cashback->product_image)
                                <img src="{{ Storage::url($cashback->product_image) }}" alt="Product Image" class="img-fluid rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="text-center p-2 bg-light border rounded" style="width: 50px; height: 50px;">لا توجد صورة</div>
                            @endif
                            استرداد نقدي على {{ $cashback->product_name }}
                        </h5>
                        <p class="card-text"><strong><i class="fas fa-info-circle"></i> تفاصيل المنتج:</strong> {{ $cashback->product_details }}</p>
                        <p class="card-text"><strong><i class="fas fa-percent"></i> قيمة الاسترداد:</strong> {{ $cashback->amount }} نقطة </p>
                        <p class="card-text"><strong><i class="fas fa-check"></i> الحالة:</strong>
                            <span id="status-{{ $cashback->id }}">{{ $cashback->active ? 'مفعل' : 'غير مفعل' }}</span>
                        </p>
                        <!-- Toggle Button -->
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-cashback" type="checkbox" role="switch" id="toggle-{{ $cashback->id }}" {{ $cashback->active ? 'checked' : '' }} data-id="{{ $cashback->id }}">
                            <label class="form-check-label" for="toggle-{{ $cashback->id }}">تفعيل/تعطيل الاسترداد</label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('cashbacks.edit', $cashback->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('cashbacks.destroy', $cashback->id) }}" method="POST" style="display: inline;">
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
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا يوجد استردادات نقدية متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination justify-content-center">
        {{ $cashbacks->appends(Request::all())->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling for the cashback details */
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

    .btn-primary, .btn-danger {
        margin-right: 5px;
        border: none;
    }

    .btn-primary:hover, .btn-danger:hover {
        opacity: 0.85;
    }

    .text-primary {
        color: #007bff !important;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Toggle cashback status via Ajax
        $('.toggle-cashback').on('change', function() {
            var cashbackId = $(this).data('id');
            var isActive = $(this).is(':checked') ? 1 : 0;
            
            $.ajax({
                url: '/cashbacks/toggle/' + cashbackId,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    active: isActive
                },
                success: function(response) {
                    $('#status-' + cashbackId).text(isActive ? 'مفعل' : 'غير مفعل');
                }
            });
        });
    });
</script>
@endsection
