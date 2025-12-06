@extends('layouts.web')

@section('title', 'حسابي الشخصي')

@section('content')
<div class="container mt-5">
    <div class="axil-dashboard-warp">
        <div class="axil-dashboard-author text-center mb-4">
            <div class="media">
                <div class="media-body mt-3">
                    <h2 class="title mb-1">مرحباً، {{ auth('customer')->user()->name }}!</h2>
                    <p class="joining-date">عضو منذ {{ \Carbon\Carbon::parse(auth('customer')->user()->created_at)->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-xl-3 col-md-4 mb-4">
                <!-- Toggle button for small screens -->
                <button class="btn btn-primary d-block d-md-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse">
                    القائمة
                </button>
                <div class="collapse show" id="sidebarCollapse">
                    <aside class="axil-dashboard-aside">
                        <nav class="axil-dashboard-nav">
                            <div class="nav flex-column nav-pills" role="tablist">
                                <a class="nav-link {{ request('my') ? '' : 'active' }}" data-bs-toggle="pill" href="#nav-dashboard" role="tab"><i class="fas fa-th-large"></i><span>الرئيسية</span></a>
                                <a class="nav-link" data-bs-toggle="pill" href="#nav-orders" role="tab"><i class="fas fa-shopping-basket"></i><span>الطلبات</span></a>
                                <a class="nav-link {{ request('my') ? 'active' : '' }}" data-bs-toggle="pill" href="#nav-account" role="tab"><i class="fas fa-user"></i><span>تفاصيل الحساب</span></a>
                                <a class="nav-link" data-bs-toggle="pill" href="#nav-points" role="tab"><i class="fas fa-star"></i><span>نقاطي</span></a>
                                <a class="nav-link" data-bs-toggle="pill" href="#nav-redemptions" role="tab"><i class="fas fa-exchange-alt"></i><span>الاستردادات</span></a>
                                <a class="nav-link" data-bs-toggle="pill" href="#nav-balance" role="tab"><i class="fas fa-wallet"></i><span>رصيدي</span></a>
                                <a class="nav-link" href="{{ route('customer-logout') }}"><i class="fas fa-sign-out-alt"></i><span>تسجيل الخروج</span></a>
                            </div>
                        </nav>
                    </aside>
                </div>
            </div>
            <!-- End Sidebar Navigation -->

            <!-- Main Content -->
            <div class="col-xl-9 col-md-8">
                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade {{ request('my') ? '' : 'show active' }}" id="nav-dashboard" role="tabpanel">
                        <div class="axil-dashboard-overview text-center">
                            <h3 class="welcome-text">مرحباً بك في حسابك الشخصي</h3>
                            <p>من هنا يمكنك إدارة تفاصيل حسابك والاطلاع على نشاطاتك.</p>
                        </div>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-pane fade" id="nav-orders" role="tabpanel">
                        <div class="axil-dashboard-order">
                            @forelse ($orders as $order)
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">رقم الطلب: #{{ $order->id }}</h5>
                                        @if ($order->payment_code)
                                            <p class="card-text">كود اللعبة: #{{ $order->payment_code }}</p>
                                        @endif
                                        <p class="card-text">التاريخ: {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</p>
                                        <p class="card-text">حالة الطلب:
                                            <span class="badge 
                                                @if($order->status == 'new') bg-secondary
                                                @elseif($order->status == 'approved') bg-success
                                                @elseif($order->status == 'canceled') bg-danger
                                                @elseif($order->status == 'under_payment') bg-warning
                                                @endif">
                                                @if($order->status == 'new')
                                                    جديد
                                                @elseif($order->status == 'approved')
                                                    مكتمل
                                                @elseif($order->status == 'canceled')
                                                    ملغي
                                                @elseif($order->status == 'under_payment')
                                                    قيد الشراء
                                                @endif
                                            </span>
                                        </p>
                                        <p class="card-text">الإجمالي: {{ $order->discounted_total }} د.ل</p>
                                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#orderDetailsModal{{ $order->id }}">عرض التفاصيل</button>
                                    </div>
                                </div>

                                <!-- Order Details Modal -->
                                <div class="modal fade" id="orderDetailsModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailsModalLabel{{ $order->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تفاصيل الطلب #{{ $order->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>المنتج</th>
                                                                <th>الفئة</th>
                                                                <th>الكمية</th>
                                                                <th>السعر</th>
                                                                <th>المتطلبات</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($order->products as $product)
                                                                <tr>
                                                                    <td>{{ $product->name }}</td>
                                                                    <td>{{ $product->variantObj ? $product->variantObj->name : 'غير محدد' }}</td>
                                                                    <td>{{ $product->quantity }}</td>
                                                                    <td>{{ $product->price }} د.ل</td>
                                                                    <td>
                                                                        @if($product->requirements->isNotEmpty())
                                                                            <ul class="list-unstyled mb-0">
                                                                                @foreach ($product->requirements as $requirement)
                                                                                    <li><strong>{{ $requirement->name }}:</strong> {{ $requirement->value }}</li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="mt-4">
                                                    <p><strong>الإجمالي:</strong> {{ $order->discounted_total }} د.ل</p>
                                                    <p><strong>الحالة:</strong>
                                                        <span class="badge 
                                                            @if($order->status == 'new') bg-secondary
                                                            @elseif($order->status == 'approved') bg-success
                                                            @elseif($order->status == 'canceled') bg-danger
                                                            @elseif($order->status == 'under_payment') bg-warning
                                                            @endif">
                                                            @if($order->status == 'new')
                                                                جديد
                                                            @elseif($order->status == 'approved')
                                                                مكتمل
                                                            @elseif($order->status == 'canceled')
                                                                ملغي
                                                            @elseif($order->status == 'under_payment')
                                                                قيد الشراء
                                                            @endif
                                                        </span>
                                                    </p>
                                                    @if ($order->notes)
                                                        <p><strong>ملاحظات:</strong> {{ $order->notes }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Order Details Modal -->
                            @empty
                                <div class="text-center">لا توجد طلبات حتى الآن.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Account Details Tab -->
                    <div class="tab-pane fade {{ request('my') ? 'show active' : '' }}" id="nav-account" role="tabpanel">
                        <div class="axil-dashboard-account">
                            @if (request('my') && (!auth()->user()->city_id || !auth()->user()->phone_number))
                                <div class="alert alert-warning">يجب إكمال بياناتك قبل إتمام أي عملية شراء.</div>
                            @endif
                            <form action="{{ route('website.profileUpdate') }}" method="POST" class="mt-4">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">الاسم</label>
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name', auth('customer')->user()->name) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone_number">رقم الهاتف</label>
                                        <input id="phone_number" type="tel" class="form-control" name="phone_number" value="{{ old('phone_number', auth('customer')->user()->phone_number) }}" placeholder="مثال: +966501234567" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email">البريد الإلكتروني</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email', auth('customer')->user()->email) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="city_id">المدينة</label>
                                        <select id="city_id" class="form-control" name="city_id" required>
                                            <option value="">اختر المدينة</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" {{ auth('customer')->user()->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password">كلمة المرور الجديدة</label>
                                        <input id="password" type="password" class="form-control" name="password">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation">تأكيد كلمة المرور</label>
                                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success w-100 mt-3">حفظ التغييرات</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Points Tab -->
                    <div class="tab-pane fade" id="nav-points" role="tabpanel">
                        <div class="axil-dashboard-points">
                            <h4 class="mb-1">نقاطي</h4>
                            <h6 class="mb-4 mt-2 text-primary">النقاط المتاحة: {{ auth('customer')->user()->cashback }} نقطة</h6>

                            <!-- Tabs for My Redemptions and New Redemption -->
                            <ul class="nav nav-tabs" id="pointsTabs" role="tablist">
                                <li class="nav-item">
                                    
                                    <a class="nav-link active" id="my-redemptions-tab" data-bs-toggle="tab" href="#my-redemptions" role="tab" aria-controls="my-redemptions" aria-selected="true">استرداداتي</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="new-redemption-tab" data-bs-toggle="tab" href="#new-redemption" role="tab" aria-controls="new-redemption" aria-selected="false">استرداد جديد</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="pointsTabsContent">
                                <!-- My Redemptions Tab Content -->
                                <div class="tab-pane fade show active" id="my-redemptions" role="tabpanel" aria-labelledby="my-redemptions-tab">
                                    <h5 class="mt-4">طلباتي السابقة:</h5>
                                    <div class="row">
                                        @forelse ($myRedemptions as $redemption)
                                            <div class="col-lg-6 mb-4">
                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <h5 class="h5 mr-3">
                                                                    @if ($redemption->cashback->product_image)
                                                                        <img src="{{ Storage::url($redemption->cashback->product_image) }}" alt="Product Image" class="img-fluid rounded" style="width: 100%; height: 120px; object-fit: cover;">
                                                                    @else
                                                                        <div class="text-center p-2 bg-light border rounded" style="width: 100%; height: 120px;">لا توجد صورة</div>
                                                                    @endif
                                                                </h5>
                                                            </div>
                                                            <div class="col-8">
                                                                <h4 class="mb-3 pb-0 text-primary">{{ $redemption->cashback->product_name }}</h4>
                                                                <p class="card-text"><strong><i class="fas fa-info-circle"></i> تفاصيل المنتج:</strong> {{ $redemption->cashback->product_details }}</p>
                                                                <p class="card-text"><strong><i class="fa fa-clock"></i> تاريخ الاسترداد:</strong> {{ date('Y-m-d', strtotime($redemption->created_at ))}}</p>
                                                                <p class="card-text"><strong><i class="fa fa-check-circle"></i> الحالة:</strong> 
                                                                    @if ($redemption->status == App\Models\Redemption::STATUS_PENDING)
                                                                        <span class="badge bg-primary">{{ App\Models\Redemption::STATUS_PENDING }}</span>
                                                                    @elseif ($redemption->status == App\Models\Redemption::STATUS_COMPLETED)
                                                                        <span class="badge bg-success">{{ App\Models\Redemption::STATUS_COMPLETED }}</span>
                                                                    @endif
                                                                </p>
                                                                <p class="card-text text-primary"><strong><i class="fas fa-star"></i> قيمة الاسترداد:</strong> {{ $redemption->cashback->amount }} نقطة</p>
                                                                <p class="card-text"><strong><i class="fa fa-edit"></i> ملاحظات:</strong> {{ $redemption->notes ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center">لا توجد طلبات سابقة</p>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- New Redemption Tab Content -->
                                <div class="tab-pane fade" id="new-redemption" role="tabpanel" aria-labelledby="new-redemption-tab">
                                    <h5 class="mt-4">المنتجات المتاحة للاستبدال:</h5>
                                    <div class="row">
                                        @forelse ($redeemableProducts as $cashback)
                                            <div class="col-lg-4 mb-4">
                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <h5 class="h5 mr-3">
                                                                    @if ($cashback->product_image)
                                                                        <img src="{{ Storage::url($cashback->product_image) }}" alt="Product Image" class="img-fluid rounded" style="width: 100%; height: 120px; object-fit: cover;">
                                                                    @else
                                                                        <div class="text-center p-2 bg-light border rounded" style="width: 100%; height: 120px;">لا توجد صورة</div>
                                                                    @endif
                                                                </h5>
                                                            </div>
                                                            <div class="col-8">
                                                                <h4 class="mb-3 pb-0 text-primary">{{ $cashback->product_name }}</h4>
                                                                <p class="card-text"><strong><i class="fas fa-info-circle"></i> تفاصيل المنتج:</strong> {{ $cashback->product_details }}</p>
                                                                <p class="card-text text-primary"><strong><i class="fas fa-star"></i> قيمة الاسترداد:</strong> {{ $cashback->amount }} نقطة</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-white border-0">
                                                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#confirmredeem_{{ $cashback->id }}">استبدال المنتج</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal for Confirmation -->
                                            <div class="modal fade" id="confirmredeem_{{ $cashback->id }}" tabindex="-1" aria-labelledby="confirmredeem_{{ $cashback->id }}Label" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">استرداد: {{ $cashback->product_name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-center mb-2">هل أنت متأكد من رغبتك في استرداد <strong>{{ $cashback->product_name }}</strong>؟</p>
                                                            <p class="text-center text-danger">سيتم خصم {{ $cashback->amount }} من رصيد نقاطك.</p>

                                                            <!-- Form to Submit Redemption -->
                                                            <form action="{{ route('redeem.cashback') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="cashback_id" value="{{ $cashback->id }}">
                                                                <textarea name="notes" placeholder="يمكنك إضافة أي ملاحظات هنا" cols="30" rows="4" class="form-control"></textarea>
                                                                <button class="btn btn-primary d-block mt-3">تأكيد</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        @empty
                                            <p class="text-center">لا توجد منتجات متاحة للاسترداد بالنقاط الحالية.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Variant Redemptions Tab -->
                    <div class="tab-pane fade" id="nav-redemptions" role="tabpanel">
                        <div class="axil-dashboard-redemptions">
                            <h4 class="mb-1"><i class="fas fa-exchange-alt"></i> الاستردادات</h4>
                            <h6 class="mb-4 mt-2 text-primary">رصيد الاسترداد المتاح: {{ auth('customer')->user()->redemption_balance ?? 0 }} نقطة</h6>

                            <!-- Tabs for My Variant Redemptions and New Variant Redemption -->
                            <ul class="nav nav-tabs" id="variantRedemptionsTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="my-variant-redemptions-tab" data-bs-toggle="tab" href="#my-variant-redemptions" role="tab">استرداداتي</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="new-variant-redemption-tab" data-bs-toggle="tab" href="#new-variant-redemption" role="tab">استرداد جديد</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="variantRedemptionsTabsContent">
                                <!-- My Variant Redemptions Tab Content -->
                                <div class="tab-pane fade show active" id="my-variant-redemptions" role="tabpanel">
                                    <h5 class="mt-4">طلبات الاسترداد السابقة:</h5>
                                    <div class="row">
                                        @forelse ($myVariantRedemptions ?? [] as $redemption)
                                            <div class="col-lg-6 mb-4">
                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                @if ($redemption->variant->product->image)
                                                                    <img src="{{ Storage::url($redemption->variant->product->image) }}" alt="Product Image" class="img-fluid rounded" style="width: 100%; height: 120px; object-fit: cover;">
                                                                @else
                                                                    <div class="text-center p-2 bg-light border rounded" style="width: 100%; height: 120px;">لا توجد صورة</div>
                                                                @endif
                                                            </div>
                                                            <div class="col-8">
                                                                <h5 class="mb-2 text-primary">{{ $redemption->variant->product->name }}</h5>
                                                                <p class="card-text mb-1"><strong>الفئة:</strong> {{ $redemption->variant->name }}</p>
                                                                <p class="card-text mb-1"><strong>التاريخ:</strong> {{ $redemption->created_at->format('Y-m-d') }}</p>
                                                                <p class="card-text mb-1"><strong>الحالة:</strong>
                                                                    @if ($redemption->status == 'pending')
                                                                        <span class="badge bg-warning">قيد التنفيذ</span>
                                                                    @elseif ($redemption->status == 'completed')
                                                                        <span class="badge bg-success">مكتمل</span>
                                                                    @elseif ($redemption->status == 'cancelled')
                                                                        <span class="badge bg-danger">ملغي</span>
                                                                    @endif
                                                                </p>
                                                                <p class="card-text text-primary"><strong>النقاط المستخدمة:</strong> {{ $redemption->amount_used }}</p>
                                                                @if($redemption->notes)
                                                                <p class="card-text"><strong>ملاحظات:</strong> {{ $redemption->notes }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center mt-4">لا توجد طلبات استرداد سابقة</p>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- New Variant Redemption Tab Content -->
                                <div class="tab-pane fade" id="new-variant-redemption" role="tabpanel">
                                    <h5 class="mt-4">المنتجات المتاحة للاسترداد:</h5>
                                    <div class="row">
                                        @forelse ($redeemableVariants ?? [] as $variant)
                                            <div class="col-lg-4 mb-4">
                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                @if ($variant->product->image)
                                                                    <img src="{{ Storage::url($variant->product->image) }}" alt="Product Image" class="img-fluid rounded" style="width: 100%; height: 100px; object-fit: cover;">
                                                                @else
                                                                    <div class="text-center p-2 bg-light border rounded" style="width: 100%; height: 100px; font-size: 10px;">لا توجد صورة</div>
                                                                @endif
                                                            </div>
                                                            <div class="col-8">
                                                                <h6 class="mb-1 text-primary">{{ $variant->product->name }}</h6>
                                                                <p class="card-text mb-1" style="font-size: 12px;"><strong>الفئة:</strong> {{ $variant->name }}</p>
                                                                <p class="card-text text-success mb-0" style="font-size: 12px;"><strong>التكلفة:</strong> {{ $variant->redemption_cost }} نقطة</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-0">
                                                        @if(auth('customer')->user()->redemption_balance >= $variant->redemption_cost)
                                                            <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#confirmVariantRedeem_{{ $variant->id }}">
                                                                <i class="fas fa-exchange-alt"></i> استرداد
                                                            </button>
                                                        @else
                                                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                                                رصيد غير كافي
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal for Variant Redemption Confirmation -->
                                            <div class="modal fade" id="confirmVariantRedeem_{{ $variant->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">استرداد: {{ $variant->product->name }} - {{ $variant->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-center mb-2">هل أنت متأكد من رغبتك في استرداد <strong>{{ $variant->name }}</strong> من <strong>{{ $variant->product->name }}</strong>؟</p>
                                                            <p class="text-center text-danger">سيتم خصم {{ $variant->redemption_cost }} نقطة من رصيد الاسترداد الخاص بك.</p>

                                                            <form action="{{ route('variant-redemptions.store') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="variant_id" value="{{ $variant->id }}">

                                                                {{-- إظهار حقول المتطلبات إذا كان المنتج يحتوي عليها --}}
                                                                @if($variant->product->requirements && $variant->product->requirements->count() > 0)
                                                                    @foreach($variant->product->requirements as $requirement)
                                                                        <div class="mb-3">
                                                                            <label for="req_{{ $variant->id }}_{{ $requirement->id }}" class="form-label">{{ $requirement->name }}</label>
                                                                            @if($requirement->type === 'list' && $requirement->listItems->count() > 0)
                                                                                <select name="requirements[{{ $requirement->id }}]" id="req_{{ $variant->id }}_{{ $requirement->id }}" class="form-control" required>
                                                                                    <option value="">اختر...</option>
                                                                                    @foreach($requirement->listItems as $listItem)
                                                                                        <option value="{{ $listItem->item }}">{{ $listItem->item }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            @else
                                                                                <input type="text" name="requirements[{{ $requirement->id }}]" id="req_{{ $variant->id }}_{{ $requirement->id }}" class="form-control" required>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                @endif

                                                                <div class="mb-3">
                                                                    <label for="notes_{{ $variant->id }}" class="form-label">ملاحظات (اختياري)</label>
                                                                    <textarea name="notes" id="notes_{{ $variant->id }}" class="form-control" rows="3" placeholder="أضف أي ملاحظات هنا..."></textarea>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary w-100">تأكيد الاسترداد</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center mt-4">لا توجد منتجات متاحة للاسترداد حالياً</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Tab -->
                    <div class="tab-pane fade" id="nav-balance" role="tabpanel">
                        <div class="axil-dashboard-balance">
                            <h4 class="mb-1">رصيدي</h4>
                            <h6 class="mb-4 mt-2 text-danger">كود العميل الخاص بك هو: {{ auth('customer')->user()->code }}</h6>
                            <div class="balance-display mb-4">
                                <h2>{{ auth('customer')->user()->balance }} د.ل</h2>
                            </div>

                            <a href="#" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#transferBalanceModal">تحويل رصيد  <i class="fa fa-exchange-alt"></i></a>
                            <div class="form-group mt-4">
                                <label for="balance-method" style="background: #0a0e1a!important;">اختر طريقة التعبئة:</label>
                                <select id="balance-method" class="form-control">
                                    <option value="">اختر</option>
                                    <option value="prepaid-card">كروت الدفع المسبق ( الخاصه RE STORE ) </option>
                                    <option value="libyana">ليبيانا</option>
                                    {{-- <option value="almadar">المدار</option> --}}
                                    <option value="vodafone-cash">فودافون كاش</option>
                                    <option value="asiacell">آسياسيل</option>
                                </select>
                            </div>

                            <!-- Prepaid Card Form -->
                            <div id="prepaid-card-form" class="mt-3" style="display: none;">
                                <form action="{{ route('customer.addBalance') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="card-number">رقم الكرت:</label>
                                        <input type="text" id="card-number" name="secret_number" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">تعبئة الرصيد</button>
                                </form>
                            </div>

                            <!-- Libyana Instructions with Logo and Conversion Rates -->
                            <div id="libyana-instructions" class="text-center mt-3" style="display: none;">
                                <img src="{{ asset('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTOrltJzI0FLlkfFuMHN_-jayKife48sB0BYA&s') }}" alt="Libyana Logo" class="mb-3" style="width: 60px;">
                                <div class="">
                                    <p class="text-dark">
                                        يمكنك التحويل وسيتم التعبئة بشكل تلقائي لحسابك يجب التأكد من ان الحساب الخاص بك مرتبط بنفس الرقم المراد التحويل القيمة منه
                                            <p class="text-primary">يجب التحويل الى الرقم :  <span class="text-primary" style="font-weight: bold!important"> {{ App\Models\Content::first()->libyana }} </span></p>
                                        </p>
                                </div>

                                @php
                                    $content = App\Models\Content::first();
                                    $pointCostLibyana = $content->point_cost_libyana ?? $content->point_cost ?? 0.01;
                                    $exampleAmount = 20;
                                    $examplePoints = round($exampleAmount / $pointCostLibyana);
                                @endphp

                                <div class="alert alert-info mt-3" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>طريقة احتساب النقاط:</strong><br>
                                    تكلفة النقطة الواحدة = <strong>{{ $pointCostLibyana }}</strong> د.ل<br>
                                    <small class="text-muted">مثال: عند تحويل {{ $exampleAmount }} د.ل ستحصل على <strong>{{ $examplePoints }}</strong> نقطة</small>
                                </div>
                            </div>

                            <!-- Almadar Instructions with Logo and Conversion Rates -->
                            <div id="almadar-instructions" class="text-center mt-3" style="display: none;">
                                <img src="{{ asset('https://customercare.almadar.ly/hc/theming_assets/01HZM4P8H5NW4FF24DETSYBSEE') }}" alt="Almadar Logo" class="mb-3" style="width: 150px;">
                                <div class="">
                                    <p class="text-dark">
                                        يمكنك التحويل وسيتم التعبئة بشكل تلقائي لحسابك يجب التأكد من ان الحساب الخاص بك مرتبط بنفس الرقم المراد التحويل القيمة منه
                                            <p class="text-primary">يجب التحويل الى الرقم :  <span class="text-primary" style="font-weight: bold!important">{{ App\Models\Content::first()->madar }}</span></p>
                                        </p>
                                </div>

                                @php
                                    $content = App\Models\Content::first();
                                    $pointCostAlmadar = $content->point_cost_almadar ?? $content->point_cost ?? 0.01;
                                    $exampleAmount = 20;
                                    $examplePoints = round($exampleAmount / $pointCostAlmadar);
                                @endphp

                                <div class="alert alert-info mt-3" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>طريقة احتساب النقاط:</strong><br>
                                    تكلفة النقطة الواحدة = <strong>{{ $pointCostAlmadar }}</strong> د.ل<br>
                                    <small class="text-muted">مثال: عند تحويل {{ $exampleAmount }} د.ل ستحصل على <strong>{{ $examplePoints }}</strong> نقطة</small>
                                </div>
                            </div>

                            <!-- Vodafone Cash Instructions with Logo and Conversion Rates -->
                            <div id="vodafone-cash-instructions" class="text-center mt-3" style="display: none;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Vodafone_icon.svg/1200px-Vodafone_icon.svg.png" alt="Vodafone Cash Logo" class="mb-3" style="width: 60px;">
                                <div class="">
                                    <p class="text-dark">
                                        يمكنك التحويل عبر فودافون كاش وسيتم التعبئة بشكل تلقائي لحسابك
                                            <p class="text-danger">يجب التحويل الى الرقم :  <span class="text-danger" style="font-weight: bold!important">{{ App\Models\Content::first()->vodafone_cash ?? 'غير محدد' }}</span></p>
                                        </p>
                                </div>

                                @php
                                    $content = App\Models\Content::first();
                                    $pointCostVfcash = $content->point_cost_vfcash ?? $content->point_cost ?? 0.01;
                                    $exampleAmount = 100;
                                    $examplePoints = round($exampleAmount / $pointCostVfcash);
                                @endphp

                                <div class="alert alert-danger mt-3" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>طريقة احتساب النقاط:</strong><br>
                                    تكلفة النقطة الواحدة = <strong>{{ $pointCostVfcash }}</strong> ج.م<br>
                                    <small class="text-muted">مثال: عند تحويل {{ $exampleAmount }} ج.م ستحصل على <strong>{{ $examplePoints }}</strong> نقطة</small>
                                </div>
                            </div>

                            <!-- Asiacell Instructions with Logo and Conversion Rates -->
                            <div id="asiacell-instructions" class="text-center mt-3" style="display: none;">
                                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/9a/Asiacell_Logo.svg/1200px-Asiacell_Logo.svg.png" alt="Asiacell Logo" class="mb-3" style="width: 100px;">
                                <div class="">
                                    <p class="text-dark">
                                        يمكنك التحويل عبر آسياسيل وسيتم التعبئة بشكل تلقائي لحسابك
                                            <p class="text-warning">يجب التحويل الى الرقم :  <span class="text-warning" style="font-weight: bold!important">{{ App\Models\Content::first()->asiacell ?? 'غير محدد' }}</span></p>
                                        </p>
                                </div>

                                @php
                                    $content = App\Models\Content::first();
                                    $pointCostAsiacell = $content->point_cost_red ?? $content->point_cost ?? 0.01;
                                    $exampleAmount = 25000;
                                    $examplePoints = round($exampleAmount / $pointCostAsiacell);
                                @endphp

                                <div class="alert alert-warning mt-3" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>طريقة احتساب النقاط:</strong><br>
                                    تكلفة النقطة الواحدة = <strong>{{ $pointCostAsiacell }}</strong> د.ع<br>
                                    <small class="text-muted">مثال: عند تحويل {{ number_format($exampleAmount) }} د.ع ستحصل على <strong>{{ $examplePoints }}</strong> نقطة</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Main Content -->


<!-- Transfer Balance Modal -->
<div class="modal fade" id="transferBalanceModal" tabindex="-1" aria-labelledby="transferBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferBalanceModalLabel">تحويل رصيد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.transferBalance') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="col-12">
                            <div class="form-group row mt-3">
                                <label for="recipient_code" class="col-md-4 col-form-label text-md-right">{{ __('كود العميل المستقبل') }}</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="recipient_code" name="recipient_code" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="col-12">
                            <div class="form-group row mt-3">
                                <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('المبلغ') }}</label>
                                <div class="col-md-12">
                                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">تحويل</button>
                </form>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Balance method selection
    document.getElementById('balance-method').addEventListener('change', function () {
        const method = this.value;
        document.getElementById('prepaid-card-form').style.display = (method === 'prepaid-card') ? 'block' : 'none';
        document.getElementById('libyana-instructions').style.display = (method === 'libyana') ? 'block' : 'none';
        document.getElementById('almadar-instructions').style.display = (method === 'almadar') ? 'block' : 'none';
        document.getElementById('vodafone-cash-instructions').style.display = (method === 'vodafone-cash') ? 'block' : 'none';
        document.getElementById('asiacell-instructions').style.display = (method === 'asiacell') ? 'block' : 'none';
    });

    // Collapse sidebar when a nav link is clicked
    var navLinks = document.querySelectorAll('.axil-dashboard-nav .nav-link');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            var sidebarCollapse = document.getElementById('sidebarCollapse');
            if (window.innerWidth < 768) {
                var bsCollapse = new bootstrap.Collapse(sidebarCollapse, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    /* Dark Mode Variables */
    :root {
        --dark-bg: #0a0e1a;
        --dark-bg-secondary: #151923;
        --dark-bg-card: #1a1f2e;
        --dark-border: #2a2f3e;
        --gold-primary: #c9a636;
        --gold-secondary: #b89530;
        --text-primary: #e4e4e7;
        --text-secondary: #a1a1aa;
        --text-muted: #71717a;
        --success-color: #22c55e;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
    }


    .axil-dashboard-aside .nav-link
    {
        right: 0px !important;
    }
    /* Dashboard Wrapper */
    .axil-dashboard-warp {
        background-color: var(--dark-bg-secondary);
        padding: 30px;
        border-radius: 12px;
        border: 1px solid var(--dark-border);
    }

    /* Author Section */
    .axil-dashboard-author .title {
        color: var(--text-primary) !important;
    }

    .axil-dashboard-author .joining-date {
        color: var(--text-secondary) !important;
    }

    /* Sidebar Navigation */
    .axil-dashboard-aside {
        background-color: var(--dark-bg-card);
        border-radius: 12px;
        padding: 15px;
        border: 1px solid var(--dark-border);
    }

    .axil-dashboard-nav .nav-link {
        color: var(--text-primary);
        background-color: var(--dark-bg-secondary);
        margin-bottom: 10px;
        border-radius: 8px;
        text-align: right;
        padding: 12px 15px;
        transition: all 0.3s ease;
        border: 1px solid var(--dark-border);
    }

    .axil-dashboard-nav .nav-link:hover {
        background-color: var(--dark-bg);
        border-color: var(--gold-primary);
        color: var(--gold-primary);
    }

    .axil-dashboard-nav .nav-link.active {
        background-color: var(--gold-primary);
        color: var(--dark-bg);
        border-color: var(--gold-primary);
    }

    .axil-dashboard-nav .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .axil-dashboard-nav .nav-link i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    /* Welcome Section */
    .axil-dashboard-overview {
        background-color: var(--dark-bg-card);
        padding: 40px;
        border-radius: 12px;
        border: 1px solid var(--dark-border);
    }

    .axil-dashboard-overview .welcome-text {
        color: var(--text-primary) !important;
    }

    .axil-dashboard-overview p {
        color: var(--text-secondary) !important;
    }

    /* Cards */
    .card {
        background-color: var(--dark-bg-card) !important;
        border: 1px solid var(--dark-border) !important;
        border-radius: 12px !important;
    }

    .card-body {
        background-color: transparent;
        border-radius: 12px;
    }

    .card-title {
        font-size: 1.25rem;
        color: var(--gold-primary) !important;
    }

    .card-text {
        color: var(--text-secondary) !important;
    }

    .card-footer {
        background-color: var(--dark-bg-secondary) !important;
        border-top: 1px solid var(--dark-border) !important;
    }

    /* Balance Display */
    .balance-display h2 {
        font-size: 2.5rem;
        color: var(--success-color);
        background-color: rgba(34, 197, 94, 0.1);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    /* Form Controls */
    .form-control {
        background-color: var(--dark-bg-secondary) !important;
        border: 1px solid var(--dark-border) !important;
        color: var(--text-primary) !important;
        border-radius: 8px;
        padding: 12px 15px;
    }

    .form-control:focus {
        background-color: var(--dark-bg) !important;
        border-color: var(--gold-primary) !important;
        box-shadow: 0 0 0 3px rgba(201, 166, 54, 0.1);
        color: var(--text-primary) !important;
    }

    .form-control::placeholder {
        color: var(--text-muted) !important;
    }

    label {
        color: var(--text-primary) !important;
        font-weight: 600;
        margin-bottom: 8px;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: var(--gold-primary) !important;
        border-color: var(--gold-primary) !important;
        color: var(--dark-bg) !important;
    }

    .btn-primary:hover {
        background-color: var(--gold-secondary) !important;
        border-color: var(--gold-secondary) !important;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: var(--dark-bg-secondary) !important;
        border-color: var(--dark-border) !important;
        color: var(--text-primary) !important;
    }

    .btn-secondary:hover {
        background-color: var(--dark-bg) !important;
        border-color: var(--gold-primary) !important;
        color: var(--gold-primary) !important;
    }

    .btn-success {
        background-color: var(--success-color) !important;
        border-color: var(--success-color) !important;
    }

    /* Modals */
    .modal-content {
        background-color: var(--dark-bg-card) !important;
        border: 1px solid var(--dark-border) !important;
        border-radius: 12px;
    }

    .modal-header {
        border-bottom: 1px solid var(--dark-border) !important;
    }

    .modal-header .modal-title {
        color: var(--text-primary) !important;
    }

    .modal-header .btn-close {
        filter: invert(1);
    }

    .modal-body {
        color: var(--text-secondary);
    }

    .modal-body p {
        color: var(--text-secondary) !important;
    }

    .modal-footer {
        border-top: 1px solid var(--dark-border) !important;
    }

    /* Tables */
    .table {
        color: var(--text-primary) !important;
    }

    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: var(--dark-bg-secondary) !important;
    }

    .table-striped > tbody > tr:nth-of-type(even) {
        background-color: var(--dark-bg-card) !important;
    }

    .table th {
        background-color: var(--dark-bg) !important;
        color: var(--gold-primary) !important;
        border-color: var(--dark-border) !important;
    }

    .table td {
        border-color: var(--dark-border) !important;
        color: var(--text-secondary) !important;
    }

    .modal-body table {
        width: 100%;
    }

    .modal-body th, .modal-body td {
        text-align: center;
        vertical-align: middle;
    }

    .modal-body ul {
        padding-left: 0;
        list-style: none;
    }

    .modal-body ul li {
        margin-bottom: 5px;
    }

    /* Tabs */
    .nav-tabs {
        border-bottom: 1px solid var(--dark-border) !important;
    }

    .nav-tabs .nav-link {
        color: var(--text-secondary) !important;
        border: none !important;
        background-color: transparent !important;
        padding: 12px 20px;
    }

    .nav-tabs .nav-link:hover {
        color: var(--gold-primary) !important;
        border: none !important;
    }

    .nav-tabs .nav-link.active {
        color: var(--gold-primary) !important;
        background-color: transparent !important;
        border-bottom: 3px solid var(--gold-primary) !important;
    }

    /* Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
    }

    .bg-success {
        background-color: var(--success-color) !important;
    }

    .bg-danger {
        background-color: var(--danger-color) !important;
    }

    .bg-warning {
        background-color: var(--warning-color) !important;
        color: var(--dark-bg) !important;
    }

    .bg-secondary {
        background-color: var(--text-muted) !important;
    }

    .bg-primary {
        background-color: var(--gold-primary) !important;
        color: var(--dark-bg) !important;
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        border: none;
    }

    .alert-warning {
        background-color: rgba(245, 158, 11, 0.15) !important;
        color: var(--warning-color) !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
    }

    .alert-info {
        background-color: rgba(59, 130, 246, 0.15) !important;
        color: #60a5fa !important;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
    }

    /* Text Colors */
    .text-primary {
        color: var(--gold-primary) !important;
    }

    .text-danger {
        color: var(--danger-color) !important;
    }

    .text-dark {
        color: var(--text-primary) !important;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    h4, h5, h6 {
        color: var(--text-primary) !important;
    }

    /* Specific Sections */
    .axil-dashboard-order,
    .axil-dashboard-account,
    .axil-dashboard-points,
    .axil-dashboard-balance {
        background-color: var(--dark-bg-card);
        padding: 25px;
        border-radius: 12px;
        border: 1px solid var(--dark-border);
    }

    /* Instructions Sections */
    #libyana-instructions,
    #almadar-instructions,
    #prepaid-card-form {
        background-color: var(--dark-bg-secondary);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--dark-border);
    }

    #libyana-instructions p,
    #almadar-instructions p {
        color: var(--text-secondary) !important;
    }

    /* Responsive */
    @media (max-width: 767px) {
        .axil-dashboard-warp {
            padding: 15px;
        }

        .axil-dashboard-aside {
            margin-bottom: 20px;
        }

        .balance-display h2 {
            font-size: 1.8rem;
        }
    }
</style>
@endsection
