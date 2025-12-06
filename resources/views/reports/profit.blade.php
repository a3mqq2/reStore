@extends('layouts.app')

@section('title', 'تقرير الأرباح')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i> العودة للتقارير
            </a>
        </div>
    </div>

    <!-- ملخص الأرباح -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-coins"></i> إجمالي المبيعات</h5>
                    <h2 class="mb-0">{{ number_format($totalRevenue, 2) }}</h2>
                    <small>نقطة</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-shopping-cart"></i> إجمالي التكلفة</h5>
                    <h2 class="mb-0">{{ number_format($totalCost, 2) }}</h2>
                    <small>نقطة</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-chart-line"></i> صافي الربح</h5>
                    <h2 class="mb-0">{{ number_format($totalProfit, 2) }}</h2>
                    <small>نقطة</small>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات الفترة -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-calendar"></i>
                <strong>الفترة:</strong> من {{ $fromDate->format('Y-m-d H:i') }} إلى {{ $toDate->format('Y-m-d H:i') }}
                |
                <strong>الحالة:</strong>
                @if($status == 'all')
                    جميع الطلبات
                @elseif($status == 'approved')
                    الطلبات المكتملة
                @elseif($status == 'new')
                    الطلبات الجديدة
                @elseif($status == 'under_payment')
                    قيد الدفع
                @elseif($status == 'canceled')
                    الملغية
                @endif
                |
                <strong>عدد الطلبات:</strong> {{ count($ordersData) }}
            </div>
        </div>
    </div>

    <!-- جدول تفاصيل الطلبات -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-list"></i> تفاصيل الطلبات
        </div>
        <div class="card-body">
            @if(count($ordersData) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>تاريخ الطلب</th>
                            <th>الحالة</th>
                            <th>المبيعات</th>
                            <th>التكلفة</th>
                            <th>الربح</th>
                            <th>نسبة الربح</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordersData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('orders.show', $data['order']->id) }}" target="_blank">
                                    #{{ $data['order']->id }}
                                </a>
                            </td>
                            <td>{{ $data['order']->customer->name ?? $data['order']->name ?? 'زائر' }}</td>
                            <td>{{ $data['order']->order_date ? \Carbon\Carbon::parse($data['order']->order_date)->format('Y-m-d H:i') : '-' }}</td>
                            <td>
                                @if($data['order']->status == 'approved')
                                    <span class="badge bg-success">مكتمل</span>
                                @elseif($data['order']->status == 'new')
                                    <span class="badge bg-primary">جديد</span>
                                @elseif($data['order']->status == 'under_payment')
                                    <span class="badge bg-warning">قيد الدفع</span>
                                @elseif($data['order']->status == 'canceled')
                                    <span class="badge bg-danger">ملغي</span>
                                @else
                                    <span class="badge bg-secondary">{{ $data['order']->status }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($data['revenue'], 2) }}</td>
                            <td>{{ number_format($data['cost'], 2) }}</td>
                            <td class="{{ $data['profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                {{ number_format($data['profit'], 2) }}
                            </td>
                            <td>
                                @if($data['revenue'] > 0)
                                    {{ number_format(($data['profit'] / $data['revenue']) * 100, 1) }}%
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="5" class="text-start">الإجمالي</th>
                            <th>{{ number_format($totalRevenue, 2) }}</th>
                            <th>{{ number_format($totalCost, 2) }}</th>
                            <th class="{{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($totalProfit, 2) }}</th>
                            <th>
                                @if($totalRevenue > 0)
                                    {{ number_format(($totalProfit / $totalRevenue) * 100, 1) }}%
                                @else
                                    -
                                @endif
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد طلبات في هذه الفترة</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-body h2 {
        font-size: 2.5rem;
        font-weight: bold;
    }
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endsection
