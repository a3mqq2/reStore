@extends('layouts.app')

@section('title', 'التقارير')

@section('content')
<div class="row">
    <!-- طلبات خلال فترة معينة -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <i class="fas fa-clipboard-list"></i> الطلبات خلال فترة معينة
            </div>
            <div class="card-body">
                <form action="{{ route('reports.orders') }}" method="GET">
                    <div class="row">
                        <div class="col-6">
                            <label for="from_date">من تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="from_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="to_date">الى تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="to_date" class="form-control" required>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="customer_id">الزبون <i class="fa fa-user"></i> </label>
                            <select name="customer_id" class="form-control select2">
                                <option value=""> الكل </option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-primary" type="submit">عرض <i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- المنتجات التي تم شراؤها خلال فترة معينة -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <i class="fas fa-clipboard-check"></i> المنتجات التي تم شراؤها خلال فترة معينة
            </div>
            <div class="card-body">
                <form action="{{ route('reports.top-products') }}" method="GET">
                    <div class="row">
                        <div class="col-6">
                            <label for="from_date">من تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="from_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="to_date">الى تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="to_date" class="form-control" required>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-primary" type="submit">عرض <i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- الزبائن الأكثر طلباً خلال فترة -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <i class="fas fa-users"></i> الزبائن الأكثر طلباً خلال فترة
            </div>
            <div class="card-body">
                <form action="{{ route('reports.top-customers') }}" method="GET">
                    <div class="row">
                        <div class="col-6">
                            <label for="from_date">من تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="from_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="to_date">الى تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="to_date" class="form-control" required>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-primary" type="submit">عرض <i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- الكوبونات المستخدمة خلال فترة -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <i class="fas fa-ticket-alt"></i> الكوبونات المستخدمة خلال فترة
            </div>
            <div class="card-body">
                <form action="{{ route('reports.used-coupons') }}" method="GET">
                    <div class="row">
                        <div class="col-6">
                            <label for="from_date">من تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="from_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="to_date">الى تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="to_date" class="form-control" required>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-primary" type="submit">عرض <i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- الطلبات حسب المدينة -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <i class="fas fa-city"></i> الطلبات حسب المدينة
            </div>
            <div class="card-body">
                <form action="{{ route('reports.top-cities') }}" method="GET">
                    <div class="row">
                        <div class="col-6">
                            <label for="from_date">من تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="from_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="to_date">الى تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="to_date" class="form-control" required>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-primary" type="submit">عرض <i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- تقرير الأرباح -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-light">
                <i class="fas fa-chart-line"></i> تقرير الأرباح
            </div>
            <div class="card-body">
                <form action="{{ route('reports.profit') }}" method="GET">
                    <div class="row">
                        <div class="col-6">
                            <label for="from_date">من تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="from_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="to_date">الى تاريخ <i class="fa fa-calendar"></i> </label>
                            <input type="datetime-local" name="to_date" class="form-control" required>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="status">حالة الطلب <i class="fa fa-filter"></i> </label>
                            <select name="status" class="form-control">
                                <option value="approved">الطلبات المكتملة</option>
                                <option value="all">جميع الطلبات</option>
                                <option value="new">الطلبات الجديدة</option>
                                <option value="under_payment">قيد الدفع</option>
                                <option value="canceled">الملغية</option>
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-success" type="submit">عرض <i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
    .card-header {
        font-size: 1.2rem;
        font-weight: bold;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
