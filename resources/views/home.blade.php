@extends('layouts.app')
@section('title', 'الصفحة الرئيسية');
@section('content')
<div class="row">
@can('orders')

<div class="row">
    <div class="col-xl-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">رصيد SMILEONE </p>
                        <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$balance}}">{{$balance}}</span> نقطة </h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                <i class="ri-coin-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div>

    <div class="col-xl-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">رصيد Moogold </p>
                        <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$moogold_balance}}">{{$moogold_balance}}</span> USD </h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                <i class="fa fa-cow text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div>

    <div class="col-6 col-md-6">
        <div class="card card-animate bg-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white"> اجمالي الطلبات </p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="text-white"><span class="counter-value" data-target="{{App\Models\Order::count()}}">{{App\Models\Order::count()}}</span> طلب  </h4>
                        <a href="{{route('orders.index')}}" class="text-white">عرض جميع الطلبات </a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="fa fa-shopping-cart text-white"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>

    <div class="col-6 col-md-6">
        <div class="card card-animate bg-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white"> اجمالي الزبائن </p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="text-white"><span class="counter-value" data-target="{{App\Models\Customer::count()}}">{{App\Models\Customer::count()}}</span> زبون  </h4>
                        <a href="{{route('customers.index')}}" class="text-white">عرض جميع الزبائن </a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="fa fa-user text-white"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>



    <div class="col-6 col-md-6">
        <div class="card card-animate bg-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white"> اجمالي المنتجات </p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="text-white"><span class="counter-value" data-target="{{App\Models\Product::count()}}">{{App\Models\Product::count()}}</span> منتج  </h4>
                        <a href="{{route('products.index')}}" class="text-white">عرض جميع المنتجات </a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="fa fa-box text-white"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>
    

    <div class="col-6 col-md-6">
        <div class="card card-animate bg-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white"> اجمالي الكوبونات </p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="text-white"><span class="counter-value" data-target="{{App\Models\Coupon::count()}}">{{App\Models\Coupon::count()}}</span> كوبون  </h4>
                        <a href="{{route('coupons.index')}}" class="text-white">عرض جميع الكوبونات </a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="fa fa-ticket text-white"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>
    

    <div class="col-6 col-md-6">
        <div class="card card-animate bg-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white"> اجمالي المدن </p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="text-white"><span class="counter-value" data-target="{{App\Models\City::count()}}">{{App\Models\City::count()}}</span> مدينة  </h4>
                        <a href="{{route('cities.index')}}" class="text-white">عرض جميع المدن </a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="fa fa-building text-white"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>


    <div class="col-6">
        <div class="card card-animate bg-dark">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white"> اجمالي الخصومات </p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="text-white"><span class="counter-value" data-target="{{App\Models\Discount::count()}}">{{App\Models\Discount::count()}}</span> خصم  </h4>
                        <a href="{{route('discounts.index')}}" class="text-white">عرض جميع الخصومات </a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="fa fa-percent text-white"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>

    <div class="col-6">
        <div class="card card-animate bg-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white"> اجمالي طرق الدفع </p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="text-white"><span class="counter-value" data-target="{{App\Models\PaymentMethod::count()}}">{{App\Models\PaymentMethod::count()}}</span> طريقة  </h4>
                        <a href="{{route('payment-methods.index')}}" class="text-white">عرض جميع طرق الدفع </a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="fa fa-credit-card text-white"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>


    <div class="col-6">
        <div class="card card-animate bg-secondary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white"> اجمالي  مستخدمين النظام  </p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="text-white"><span class="counter-value" data-target="{{App\Models\User::count()}}">{{App\Models\User::count()}}</span> مستخدم  </h4>
                        <a href="{{route('users.index')}}" class="text-white">عرض جميع  المستخدمين </a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded fs-3">
                            <i class="fa fa-users text-white"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>

</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card crm-widget">
            <div class="card-body p-0">
                <div class="row row-cols-md-4 row-cols-1">
                    <!-- Orders Today -->
                    <div class="col col-lg border-end">
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">اجمالي طلبات اليوم</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-shopping-bag-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0">
                                        <span class="counter-value" data-target="{{ \App\Models\Order::whereDate('created_at', \Carbon\Carbon::today())->count() }}">
                                            {{ \App\Models\Order::whereDate('created_at', \Carbon\Carbon::today())->count() }}
                                        </span> طلب
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Orders This Week -->
                    <div class="col col-lg border-end">
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">اجمالي طلبات هذا الأسبوع</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-shopping-bag-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0">
                                        <span class="counter-value" data-target="{{ \App\Models\Order::whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->count() }}">
                                            {{ \App\Models\Order::whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->count() }}
                                        </span> طلب
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Orders This Month -->
                    <div class="col col-lg border-end">
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">اجمالي طلبات هذا الشهر</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-shopping-bag-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0">
                                        <span class="counter-value" data-target="{{ \App\Models\Order::whereMonth('created_at', \Carbon\Carbon::now()->month)->count() }}">
                                            {{ \App\Models\Order::whereMonth('created_at', \Carbon\Carbon::now()->month)->count() }}
                                        </span> طلب
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Orders This Year -->
                    <div class="col col-lg">
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">اجمالي طلبات هذه السنة</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-shopping-bag-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0">
                                        <span class="counter-value" data-target="{{ \App\Models\Order::whereYear('created_at', \Carbon\Carbon::now()->year)->count() }}">
                                            {{ \App\Models\Order::whereYear('created_at', \Carbon\Carbon::now()->year)->count() }}
                                        </span> طلب
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div>

@endcan
@endsection
