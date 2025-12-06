@extends('layouts.app')

@section('title', 'سجل الرصيد - ' . $customer->name)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-history"></i> سجل رصيد: {{ $customer->name }}</h4>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> العودة للزبائن
                </a>
            </div>

            <!-- Customer Info Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong><i class="fas fa-user"></i> الاسم:</strong> {{ $customer->name }}
                        </div>
                        <div class="col-md-3">
                            <strong><i class="fas fa-phone"></i> الهاتف:</strong> {{ $customer->phone_number }}
                        </div>
                        <div class="col-md-3">
                            <strong><i class="fas fa-envelope"></i> البريد:</strong> {{ $customer->email }}
                        </div>
                        <div class="col-md-3">
                            <strong><i class="fas fa-wallet"></i> الرصيد الحالي:</strong>
                            <span class="badge bg-success fs-6">{{ number_format($customer->balance, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-list"></i> سجل المعاملات
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>النوع</th>
                                    <th>المبلغ</th>
                                    <th>الرصيد قبل</th>
                                    <th>الرصيد بعد</th>
                                    <th>الوصف</th>
                                    <th>بواسطة</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>
                                        @if(in_array($transaction->type, ['add', 'transfer_in', 'refund']))
                                            <span class="badge bg-success">{{ $transaction->type_arabic }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $transaction->type_arabic }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($transaction->type, ['add', 'transfer_in', 'refund']))
                                            <span class="text-success">+{{ number_format($transaction->amount, 2) }}</span>
                                        @else
                                            <span class="text-danger">-{{ number_format($transaction->amount, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($transaction->balance_before, 2) }}</td>
                                    <td>{{ number_format($transaction->balance_after, 2) }}</td>
                                    <td>{{ $transaction->description ?? '-' }}</td>
                                    <td>{{ $transaction->performedBy?->name ?? 'النظام' }}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد معاملات مسجلة لهذا العميل</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endsection
