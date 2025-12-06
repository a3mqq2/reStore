@extends('layouts.app')

@section('title', 'إدارة الحسابات')

@section('content')
<div class="container">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-success" href="{{route('accounts.create')}}">
                    <i class="fas fa-plus"></i> إضافة حساب جديد
                </a>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('accounts.index') }}" class="d-flex gap-2">
                    <select name="category_id" class="form-select">
                        <option value="">جميع التصنيفات</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>متاح</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>مباع</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> فلترة
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>التصنيف</th>
                            <th>العنوان</th>
                            <th>اسم المستخدم</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>الزبون</th>
                            <th>تاريخ البيع</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td>{{ $account->id }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $account->category->name }}
                                    </span>
                                </td>
                                <td>{{ $account->title }}</td>
                                <td>
                                    <code>{{ $account->username }}</code>
                                </td>
                                <td>
                                    <strong>{{ number_format($account->price, 2) }}</strong> د.ل
                                </td>
                                <td>
                                    @if($account->status == 'available')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> متاح
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times-circle"></i> مباع
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($account->customer)
                                        <a href="{{ route('customers.show', $account->customer) }}" class="text-decoration-none">
                                            {{ $account->customer->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($account->sold_at)
                                        {{ $account->sold_at->format('Y-m-d H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $account->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    <i class="fas fa-info-circle"></i> لا توجد حسابات
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($accounts as $account)
    <div class="modal fade" id="deleteModal-{{ $account->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('accounts.destroy', $account) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">حذف الحساب</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من حذف الحساب "<strong>{{ $account->title }}</strong>"؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">نعم، احذف</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
