

@can('orders')
<li class="nav-item">
    <a class="nav-link menu-link" href="#orders" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-shopping-cart"></i><span data-key="t-layouts"> الطلبات </span>
    </a>
    <div class="collapse menu-dropdown" id="orders">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('orders.create') }}" class="nav-link" data-key="t-horizontal"> إضافة طلب جديد </a>
                <a href="{{ route('orders.index',['status' => "new"]) }}" class="nav-link" data-key="t-horizontal"> عرض جميع الطلبات الجديدة </a>
                <a href="{{ route('orders.index',['status' => "under_payment"]) }}" class="nav-link" data-key="t-horizontal"> عرض جميع الطلبات قيد الشراء </a>
                <a href="{{ route('orders.index',['status' => "approved"]) }}" class="nav-link" data-key="t-horizontal"> عرض جميع الطلبات المكتملة </a>
                <a href="{{ route('orders.index',['status' => "canceled"]) }}" class="nav-link" data-key="t-horizontal"> عرض جميع الطلبات الملغية </a>
                <a href="{{ route('orders.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع الطلبات </a>
            </li>
        </ul>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('orders.rated') }}">
        <i class="fa fa-star"></i><span> الطلبات ذات التقييم </span>
    </a>
</li>
@endcan


@can('customers')
<li class="nav-item">
    <a class="nav-link menu-link" href="#customers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user"></i><span data-key="t-layouts"> الزبائن </span>
    </a>
    <div class="collapse menu-dropdown" id="customers">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('customers.create') }}" class="nav-link" data-key="t-horizontal"> إضافة زبون جديد </a>
                <a href="{{ route('customers.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع الزبائن </a>
            </li>
        </ul>
    </div>
</li>
@endcan


@can('products')
<li class="nav-item">
    <a class="nav-link menu-link" href="#products" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-box"></i><span data-key="t-layouts"> المنتجات  </span>
    </a>
    <div class="collapse menu-dropdown" id="products">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('products.create') }}" class="nav-link" data-key="t-horizontal"> إضافة   منتج جديد </a>
                <a href="{{ route('products.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع  المنتجات </a>
            </li>
        </ul>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link menu-link" href="#productCategories" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-th-list"></i><span data-key="t-layouts"> الفئات </span>
    </a>
    <div class="collapse menu-dropdown" id="productCategories">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('product_categories.create') }}" class="nav-link" data-key="t-horizontal"> إضافة فئة جديدة </a>
                <a href="{{ route('product_categories.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع الفئات </a>
            </li>
        </ul>
    </div>
</li>
@endcan

<li class="nav-item">
    <a class="nav-link menu-link" href="#accounts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-circle"></i><span data-key="t-layouts"> الحسابات للبيع </span>
    </a>
    <div class="collapse menu-dropdown" id="accounts">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('account-categories.index') }}" class="nav-link" data-key="t-horizontal"> تصنيفات الحسابات </a>
                <a href="{{ route('account-categories.create') }}" class="nav-link" data-key="t-horizontal"> إضافة تصنيف جديد </a>
                <a href="{{ route('accounts.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع الحسابات </a>
                <a href="{{ route('accounts.create') }}" class="nav-link" data-key="t-horizontal"> إضافة حساب جديد </a>
            </li>
        </ul>
    </div>
</li>



@can('coupons')
<li class="nav-item">
    <a class="nav-link menu-link" href="#coupons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-ticket"></i><span data-key="t-layouts"> الكوبونات  </span>
    </a>
    <div class="collapse menu-dropdown" id="coupons">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('coupons.create') }}" class="nav-link" data-key="t-horizontal"> إضافة   كوبون جديد </a>
                <a href="{{ route('coupons.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع  الكوبونات </a>
            </li>
        </ul>
    </div>
</li>
@endcan





@can('customers')
<li class="nav-item">
    <a class="nav-link menu-link" href="#cards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa-regular fa-credit-card"></i> <span data-key="t-layouts"> بطاقات الرصيد  </span>
    </a>
    <div class="collapse menu-dropdown" id="cards">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('cards.create') }}" class="nav-link" data-key="t-horizontal"> إضافة   بطاقة جديدة </a>
                <a href="{{ route('cards.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع  البطاقات </a>
            </li>
        </ul>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link menu-link" href="#messages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa-regular fa-message"></i> <span data-key="t-layouts">  رسائل التحويل   </span>
    </a>
    <div class="collapse menu-dropdown" id="messages">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('messages.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع  الرسائل </a>
            </li>
        </ul>
    </div>
</li>
@endcan








@can('discounts')
<li class="nav-item">
    <a class="nav-link menu-link" href="#discounts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-percent"></i><span data-key="t-layouts"> الخصومات </span>
    </a>
    <div class="collapse menu-dropdown" id="discounts">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('discounts.create') }}" class="nav-link" data-key="t-horizontal"> إضافة طلب جديد </a>
                <a href="{{ route('discounts.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع الطلبات </a>
            </li>
        </ul>
    </div>
</li>
@endcan


@can('cities')
<li class="nav-item">
    <a class="nav-link menu-link" href="#cities" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-building"></i><span data-key="t-layouts"> المدن </span>
    </a>
    <div class="collapse menu-dropdown" id="cities">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('cities.create') }}" class="nav-link" data-key="t-horizontal"> إضافة مدينة جديدة </a>
                <a href="{{ route('cities.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع المدن </a>
            </li>
        </ul>
    </div>
</li>
@endcan







@can('payment_methods')
<li class="nav-item">
    <a class="nav-link menu-link" href="#payment_methods" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-credit-card"></i><span data-key="t-layouts"> طرق الدفع </span>
    </a>
    <div class="collapse menu-dropdown" id="payment_methods">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('payment-methods.create') }}" class="nav-link" data-key="t-horizontal"> إضافة طريقة دفع جديدة </a>
                <a href="{{ route('payment-methods.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع طرق الدفع </a>
            </li>
        </ul>
    </div>
</li>
@endcan




@can('content')
<li class="nav-item">
    <a class="nav-link menu-link" href="{{route('content.index')}}">
        <i class="ri-earth-fill"></i> <span>محتوى الموقع</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link menu-link" href="{{route('faqs.index')}}">
        <i class="fa fa-question-circle" aria-hidden="true"></i> <span> الاسئلة الشائعة </span>
    </a>
</li>
@endcan



@can('reports')
<li class="nav-item">
    <a class="nav-link menu-link" href="{{route('reports.index')}}">
        <i class=" ri-profile-line"></i> <span> التقارير </span>
    </a>
</li>
@endcan

<li class="nav-item">
    <a class="nav-link menu-link" href="#redemptions" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-exchange-alt"></i><span data-key="t-layouts"> الاستردادات </span>
    </a>
    <div class="collapse menu-dropdown" id="redemptions">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('redemptions.index') }}" class="nav-link" data-key="t-horizontal"> استردادات النقاط </a>
                <a href="{{ route('variant-redemptions.index') }}" class="nav-link" data-key="t-horizontal"> استردادات المتغيرات </a>
                <a href="{{ route('cashbacks.index') }}" class="nav-link" data-key="t-horizontal"> منتجات الاسترداد </a>
            </li>
        </ul>
    </div>
</li>



@can('users')
<li class="nav-item">
    <a class="nav-link menu-link" href="#users" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-users"></i><span data-key="t-layouts">     المستخدمين  </span>
    </a>
    <div class="collapse menu-dropdown" id="users">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('users.create') }}" class="nav-link" data-key="t-horizontal">  اضافه مستخدم جديد  </a>
                <a href="{{ route('users.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع   المستخدمين  </a>
            </li>
        </ul>
    </div>
</li>
@endcan


