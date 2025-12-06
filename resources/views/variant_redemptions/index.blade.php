@extends('layouts.app')

@section('title', 'طلبات الاسترداد')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-exchange-alt"></i> طلبات استرداد المتغيرات</span>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>الزبون</th>
                                    <th>المنتج</th>
                                    <th>الفئة</th>
                                    <th>النقاط المستخدمة</th>
                                    <th>الحالة</th>
                                    <th>الملاحظات</th>
                                    <th>التاريخ</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($redemptions as $redemption)
                                    <tr>
                                        <td>{{ $redemption->id }}</td>
                                        <td>
                                            <a href="{{ route('customers.show', $redemption->customer_id) }}">
                                                {{ $redemption->customer->name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $redemption->customer->phone_number }}</small>
                                        </td>
                                        <td>{{ $redemption->variant->product->name }}</td>
                                        <td>{{ $redemption->variant->name }}</td>
                                        <td>{{ $redemption->amount_used }}</td>
                                        <td>
                                            @if($redemption->status == 'pending')
                                                <span class="badge bg-warning">قيد التنفيذ</span>
                                            @elseif($redemption->status == 'completed')
                                                <span class="badge bg-success">مكتمل</span>
                                            @elseif($redemption->status == 'cancelled')
                                                <span class="badge bg-danger">ملغي</span>
                                            @endif
                                        </td>
                                        <td>{{ $redemption->notes ?? '-' }}</td>
                                        <td>{{ $redemption->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($redemption->status == 'pending')
                                                    <form action="{{ route('variant-redemptions.update-status', $redemption->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('هل أنت متأكد من إكمال الطلب؟')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('variant-redemptions.update-status', $redemption->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من إلغاء الطلب؟ سيتم إرجاع النقاط للزبون.')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">لا توجد طلبات استرداد</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $redemptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
