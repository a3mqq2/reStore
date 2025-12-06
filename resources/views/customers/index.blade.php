@extends('layouts.app')

@section('title', 'قائمة الزبائن')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('customers.create') }}" class="btn btn-success float-right mb-3">
                <i class="fas fa-plus"></i> إضافة جديد
            </a>
            <!-- Filter form inside a card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-search"></i> البحث عن الزبائن
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="form-control mb-3" name="name" placeholder="ابحث بالاسم" value="{{ request('name') }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control mb-3" name="city_id">
                                    <option value="">اختر المدينة</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="email" class="form-control mb-3" name="email" placeholder="ابحث بالبريد الإلكتروني" value="{{ request('email') }}">
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

            <!-- Customers display as cards -->
            <div class="row">
                @forelse ($customers as $customer)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-user"></i> {{ $customer->name }}
                                </h5>
                                <p class="card-text"><strong><i class="fas fa-city"></i> المدينة:</strong> {{ $customer->city?->name }}</p>
                                <p class="card-text"><strong><i class="fas fa-phone"></i> رقم الهاتف:</strong> {{ $customer->phone_number }}</p>
                                <p class="card-text"><strong><i class="fas fa-envelope"></i> البريد الإلكتروني:</strong> {{ $customer->email }}</p>
                                <p class="card-text"><strong><i class="fas fa-key"></i> كود العميل:</strong> {{ $customer->code }}</p>
                                <p class="card-text"><strong><i class="fas fa-wallet"></i> الرصيد:</strong> <span class="badge bg-success fs-6">{{ number_format($customer->balance, 2) }}</span></p>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addBalanceModal{{ $customer->id }}">
                                        <i class="fas fa-plus"></i> إضافة رصيد
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#subtractBalanceModal{{ $customer->id }}">
                                        <i class="fas fa-minus"></i> خصم رصيد
                                    </button>
                                    <a href="{{ route('customers.balanceHistory', $customer->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-history"></i> السجل
                                    </a>
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Add Balance Modal -->
                            <div class="modal fade" id="addBalanceModal{{ $customer->id }}" tabindex="-1" aria-labelledby="addBalanceModalLabel{{ $customer->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="addBalanceModalLabel{{ $customer->id }}"><i class="fas fa-plus"></i> إضافة رصيد - {{ $customer->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('customers.addBalance', $customer->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">الرصيد الحالي</label>
                                                    <input type="text" class="form-control" value="{{ number_format($customer->balance, 2) }}" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="amount" class="form-label">المبلغ المراد إضافته</label>
                                                    <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">الوصف (اختياري)</label>
                                                    <input type="text" class="form-control" id="description" name="description" placeholder="سبب الإضافة">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> إضافة الرصيد</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Subtract Balance Modal -->
                            <div class="modal fade" id="subtractBalanceModal{{ $customer->id }}" tabindex="-1" aria-labelledby="subtractBalanceModalLabel{{ $customer->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title" id="subtractBalanceModalLabel{{ $customer->id }}"><i class="fas fa-minus"></i> خصم رصيد - {{ $customer->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('customers.subtractBalance', $customer->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">الرصيد الحالي</label>
                                                    <input type="text" class="form-control" value="{{ number_format($customer->balance, 2) }}" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="amount" class="form-label">المبلغ المراد خصمه</label>
                                                    <input type="number" step="0.01" min="0.01" max="{{ $customer->balance }}" class="form-control" id="amount" name="amount" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">الوصف (اختياري)</label>
                                                    <input type="text" class="form-control" id="description" name="description" placeholder="سبب الخصم">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-warning"><i class="fas fa-minus"></i> خصم الرصيد</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center"><i class="fas fa-exclamation-circle"></i> لا يوجد زبائن متاحين</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination links -->
            <div class="pagination justify-content-center">
                {{ $customers->appends(Request::all())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling for the customer details */
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

    .img-fluid {
        max-height: 200px;
        object-fit: cover;
    }

    .text-primary {
        color: #007bff !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
