@extends('layouts.app')

@section('title', 'إدارة الاستردادات النقدية')

@section('content')
<div class="container">
    <!-- Filter form inside a card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search"></i> البحث عن الاستردادات
        </div>
        <div class="card-body">
            <form action="{{ route('redemptions.index') }}" method="GET">
                <div class="row">
                    <!-- Status Filter -->
                    <div class="col-md-3">
                        <label for="">الحالة</label>
                        <select class="form-control mb-3" name="status">
                            <option value="">اختر حالة الاسترداد</option>
                            <option value="{{ App\Models\Redemption::STATUS_PENDING }}" {{ request('status') == App\Models\Redemption::STATUS_PENDING ? 'selected' : '' }}>قيد التنفيذ</option>
                            <option value="{{ App\Models\Redemption::STATUS_COMPLETED }}" {{ request('status') == App\Models\Redemption::STATUS_COMPLETED ? 'selected' : '' }}>منفذ</option>
                        </select>
                    </div>

                    <!-- Date Range Filters -->
                    <div class="col-md-3">
                        <label for="">من تاريخ</label>
                        <input type="date" class="form-control mb-3" name="start_date" placeholder="من تاريخ" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="">الى تاريخ</label>
                        <input type="date" class="form-control mb-3" name="end_date" placeholder="إلى تاريخ" value="{{ request('end_date') }}">
                    </div>

                    <!-- Search Button -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Redemptions display as cards -->
    <div class="row">
        @forelse ($redemptions as $redemption)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <!-- Display Product Image -->
                    @if ($redemption->cashback->product_image)
                        <img src="{{ Storage::url($redemption->cashback->product_image) }}" alt="Product Image" class="img-fluid rounded mb-3" style="width: 100%; height: 150px; object-fit: cover;">
                    @else
                        <div class="text-center p-2 bg-light border rounded" style="width: 100%; height: 150px;">لا توجد صورة</div>
                    @endif
                    
                    <h4 class="card-title text-primary">
                        {{ $redemption->cashback->product_name }}
                    </h4>

                    <!-- Product Details -->
                    <p class="card-text"><strong><i class="fas fa-info-circle"></i> تفاصيل المنتج:</strong> {{ $redemption->cashback->product_details }}</p>
                    
                    <!-- Customer Details -->
                    <p class="card-text"><strong><i class="fas fa-user"></i> تفاصيل الزبون:</strong></p>
                    <p class="card-text"><strong>الاسم:</strong> {{ $redemption->customer->name }}</p>
                    <p class="card-text"><strong>رقم الهاتف:</strong> {{ $redemption->customer->phone_number }}</p>

                    <!-- Redemption Details -->
                    <p class="card-text"><strong><i class="fa fa-clock"></i>  تاريخ الاسترداد :</strong> {{ date('Y-m-d', strtotime($redemption->created_at)) }}</p>
                    <p class="card-text"><strong><i class="fa fa-check-circle"></i> الحالة :</strong> 
                        @if ($redemption->status == App\Models\Redemption::STATUS_PENDING)
                            <span class="badge badge-primary bg-primary">{{ App\Models\Redemption::STATUS_PENDING }}</span>
                        @elseif ($redemption->status == App\Models\Redemption::STATUS_COMPLETED)
                            <span class="badge badge-success bg-success">{{ App\Models\Redemption::STATUS_COMPLETED }}</span>
                        @endif
                    </p>
                    
                    <p class="card-text text-primary"><strong><i class="fas fa-star"></i> قيمة الاسترداد:</strong> {{ $redemption->cashback->amount }} نقطة</p>
                    <p class="card-text"><strong><i class="fa fa-edit"></i>  ملاحظات :</strong> {{ $redemption->notes ?? '-' }}</p>

                    <!-- تنفيذ Button to Trigger the Modal -->
                    @if ($redemption->status == App\Models\Redemption::STATUS_PENDING)
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmCompleteModal_{{ $redemption->id }}">
                            تنفيذ
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <div class="col-12">
                <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا يوجد استردادات نقدية متاحة</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination justify-content-center">
        {{ $redemptions->appends(Request::all())->links() }}
    </div>

    <!-- Modals for Completing Redemption -->
    @foreach ($redemptions as $redemption)
    <div class="modal fade" id="confirmCompleteModal_{{ $redemption->id }}" tabindex="-1" aria-labelledby="confirmCompleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCompleteModalLabel">تأكيد تغيير الحالة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد أنك تريد تغيير حالة الاسترداد إلى "منفذ"؟</p>
                </div>
                <div class="modal-footer">
                    <!-- Form to Update Status -->
                    <form action="{{ route('redemption.complete', $redemption->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">نعم، تنفيذ</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
