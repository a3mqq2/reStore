@extends('layouts.web')

@section('title', 'الصفحة الرئيسية')

@section('content')
<!-- Start Slider Area -->
<div class="banner-slider-wrapper">
    <div class="swiper-container banner-swiper">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <a href="{{$banner->link ? url($banner->link) : ''}}" class="banner-link">
                        <img src="{{Storage::url($banner->image)}}" alt="Banner Image" class="banner-image">
                    </a>
                </div>
            @endforeach
        </div>
        <!-- Navigation buttons -->
        <div class="swiper-button-next banner-nav-btn"></div>
        <div class="swiper-button-prev banner-nav-btn"></div>
        <!-- Pagination -->
        <div class="swiper-pagination banner-pagination"></div>
    </div>
</div>



@if (count($categories))
<div class="service-area pt-4 pb-4" style="margin-top: 100px !important">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="title">التصفح بالاقسام</h2>
        </div>
        <div class="row row-cols-xl-12 row-cols-sm-12 row-cols-1 row--20">
            @foreach ($categories as $category)
            <a href="{{route('website.category', $category)}}">
                <div class="col-6">
                    <div class="service-box border p-4 service-style-2">
                        <div class="icon">
                            @if ($category->image)
                                <img src="{{Storage::url($category->image)}}" alt="Service">
                                @else 
                                <div class="text-muted mb-3" style="height: 40px; background: #f8f9fa; display: flex; justify-content: center; align-items: center;">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="content" style="display: flex;align-items: center;">
                            <h6 class="title"> {{$category->name}} </h6>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Start Accounts for Sale Area -->
@if(isset($accounts) && count($accounts) > 0)
<div class="service-area pt-4 pb-4" style="margin-top: 130px;">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="title">حسابات للبيع</h2>
            <a href="/shop/accounts" class="btn btn-primary btn-sm">عرض الكل</a>
        </div>
        <div class="row g-3">
            @foreach ($accounts as $account)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="/shop/accounts/{{ $account->id }}" class="account-card">
                    <div class="account-box h-100">
                        <div class="account-image">
                            @if ($account->category && $account->category->image)
                                <img src="{{Storage::url($account->category->image)}}" alt="{{$account->title}}">
                            @else
                                <div class="account-placeholder-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                            @endif
                        </div>
                        <div class="account-info">
                            <h3 class="account-title">{{$account->title}}</h6>
                            @if($account->category)
                                <span class="account-category">{{ $account->category->name }}</span>
                            @endif
                            <div class="account-price text-white">{{ number_format($account->price) }}  د.ل </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<!-- End Accounts for Sale Area -->

{{-- <div class="service-area pt-4 pb-4" style="margin-top: 100px !important">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="title">التصفح بالاقسام</h2>
        </div>
        <div class="row row-cols-xl-4 row-cols-sm-2 row-cols-1 row--20">
            <div class="col">
                <a href="{{route('category', ['category_id' => 1])}}">
                    <div class="service-box border p-4 service-style-2">
                        <div class="icon">
                            <img src="./assets/images/game-controller.png" alt="Service">
                        </div>
                        <div class="content" style="display: flex;align-items: center;">
                            <h6 class="title"> الشحن بالID </h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{route('category', ['category_id' => 2])}}">
                    <div class="service-box border p-4 service-style-2">
                        <div class="icon">
                            <img src="./assets/images/coupon.png" alt="Service">
                        </div>
                        <div class="content" style="display: flex;align-items: center;">
                            <h6 class="title">أكواد الالعاب</h6>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div> --}}

<!-- Start Best Sellers Product Area  -->
<div class="axil-best-seller-product-area axil-section-gap pb--50 pb_sm--30">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="title">الألعاب</h2>
        </div>

        <!-- Category Tabs -->
        <div class="category-tabs mb-4">
            <button class="category-tab active" data-category="all">الكل</button>
            <button class="category-tab" data-category="1">شحن بالـ ID</button>
            <button class="category-tab" data-category="2">البطاقات</button>
        </div>

        <div class="row" id="products-grid">
            @foreach ($products as $product)
                <div class="col-xl-4 col-md-6 col-sm-6 col-6 product-item" data-category="{{ $product->product_category_id }}">
                    <div class="slick-single-layout">
                        <div class="axil-product product-style-three dark-product-card">
                            <div class="thumbnail position-relative">
                                <a href="{{ route('website.show-product', $product) }}">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Images">
                                </a>

                                @if ($product->discount && $product->discount->discount_percentage)
                                    <div class="discount-badge">
                                        -{{ $product->discount->discount_percentage }}%
                                    </div>
                                @endif
                            </div>

                            <div class="product-content">
                                <div class="inner">
                                    <h5 class="title text-right product-title-dark">
                                        <a href="{{ route('website.show-product', $product) }}">{{ $product->name }}</a>
                                    </h5>

                                    @php
                                    // Get the minimum price from active variants using calculated_price
                                    // This will automatically use SmileOne or MooGold based on availability
                                    $theLessPrice = $product->variants
                                        ->where('is_active', 1)
                                        ->filter(function($variant) {
                                            return $variant->calculated_price != null;
                                        })
                                        ->min('calculated_price');

                                    $discountedPrice = $theLessPrice;
                                    if ($theLessPrice && $product->discount && $product->discount->discount_percentage) {
                                        $discount = $theLessPrice * ($product->discount->discount_percentage / 100);
                                        $discountedPrice = $theLessPrice - $discount;
                                    }
                                @endphp

                                    <h6 class="product-price-dark">
                                        <span class="price-label">السعر يبدأ من:</span>
                                        @if ($discountedPrice && $discountedPrice != $theLessPrice)
                                            <del class="old-price">{{ $theLessPrice }} نقطة</del>
                                            <span class="new-price">{{ $discountedPrice }} نقطة</span>
                                        @elseif($theLessPrice)
                                            <span class="new-price">{{ $theLessPrice }} نقطة</span>
                                        @else
                                            <span class="unavailable">غير متوفر</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>



<!-- Start Testimonial Slider Area -->
<div class="testimonial-slider-area axil-testimonial-area axil-section-gap" style="padding-top: 20px!important;">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="title">آراء العملاء</h2>
        </div>
        <div class="owl-carousel owl-theme">
            <div class="row">
                @foreach($ratedOrders as $order)
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4 p-3">
                    <div class="testimonial-card-dark">
                        <div class="testimonial-content-dark">
                            <div class="quote-icon">
                                <i class="fas fa-quote-right"></i>
                            </div>
                            <p class="testimonial-description">{{$order->rating_notes}}</p>
                            <div class="customer-rating-dark">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star{{ $i <= $order->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <div class="customer-info">
                                <img class="customer-avatar" src="{{asset('https://ui-avatars.com/api/?name='.implode('+',explode(' ',$order->customer->name)))}}&background=c9a636&color=0a0e1a" alt="Header Avatar">
                                <p class="customer-name-dark">{{ $order->customer->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
<!-- End Testimonial Slider Area -->


<!-- FAQs Section -->
<!-- FAQs Section -->

@if (count($faqs))


@endif
<!-- End FAQs Section -->

@endsection

@section('styles')
<style>
    .accordion-button {
        font-size: 1.25rem; /* Increase the font size */
        font-weight: bold;
        color: #333;
        background-color: #f8f9fa;
        border: none;
        border-radius: 5px;
        padding: 15px; /* Increase padding */
    }
    .accordion-button:focus {
        box-shadow: none;
    }
    .accordion-button.collapsed {
        color: #6c757d;
    }
    .accordion-button:not(.collapsed) {
        color: #007bff;
    }
    .accordion-item {
        border: none;
    }
    .accordion-body {
        font-size: 1.2rem; /* Larger font size for the answer */
        color: #666;
        padding: 20px; /* Add more padding to the body */
    }
    .section-title-wrapper .title {
        font-size: 2.5rem; /* Larger title */
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }
    .text-muted {
        font-size: 1.25rem; /* Increase subtitle font size */
        color: #6c757d;
    }
    .btn-primary {
        font-size: 1.2rem; /* Larger button font size */
        padding: 10px 20px; /* Increase padding */
    }
</style>

<style>
/* فئة الكارد الصغيرة */
.small-card {
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 10px;
    background-color: #f9f9f9;
    max-width: 90% !important; /* تصغير العرض */
    margin: 0 auto; /* محاذاة الكارد في المنتصف */
}

/* تصغير حجم الـ swiper */
.small-card .swiper-container {
    width: 100%;
    height: 120px; /* تصغير الارتفاع بشكل كبير */
    padding: 5px; /* مسافة حول الصور */
}

/* تكبير الصور داخل السلايدر */
.small-card .swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* لضمان تغطية الصورة الكاملة */
}

/* للأجهزة المتوسطة */
@media (min-width: 768px) and (max-width: 1023px) {
    .small-card .swiper-container {
        height: 150px; /* ارتفاع أصغر للـ swiper */
    }
}

/* للأجهزة الصغيرة */
@media (max-width: 767px) {
    .small-card .swiper-container {
        height: 100px; /* تصغير الارتفاع أكثر للهواتف */
    }
}

</style>
<style>
    /* Category Tabs */
    .category-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .category-tab {
        padding: 12px 28px;
        border: 2px solid #fff;
        background: transparent;
        color: #fff;
        border-radius: 30px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-tab:hover {
        background: #c9a636;
        border-color: #c9a636;
        color: #0a0e1a;
    }

    .category-tab.active {
        background: #c9a636;
        border-color: #c9a636;
        color: #0a0e1a;
    }

    .product-item {
        transition: all 0.3s ease;
    }

    .product-item.hidden {
        display: none;
    }

    /* Discount Badge styles */
    .discount-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: #ff4d4d;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 1rem;
      font-weight: bold;
    }
    
    
    
    .axil-testimonial {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 10px;
    background-color: #f9f9f9;
}

.testimonial-content {
    text-align: center;
}

.customer-rating i {
    color: #FFD700; /* Gold color for stars */
    font-size: 1.2rem;
}

.customer-name {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
    margin-top: 10px;
}

/* Add spacing for the owl carousel */
.owl-carousel .item {
    margin: 15px;
}

    </style>

    <style>
/* قاعدة عامة */
.swiper-container {
    width: 100%;
    height: 250px; /* الارتفاع الافتراضي */
}

/* لأجهزة اللابتوب */
@media (min-width: 1024px) {
    .swiper-container {
        height: 300px; /* ارتفاع أصغر لشاشات اللابتوب */
    }
}

/* لأجهزة الآيباد (الشاشات المتوسطة) */
@media (min-width: 768px) and (max-width: 1023px) {
    .swiper-container {
        height: 250px; /* ارتفاع أصغر لشاشات الآيباد */
    }
}

/* للأجهزة الصغيرة (مثل الهواتف الذكية) */
@media (max-width: 767px) {
    .swiper-container {
        height: 200px; /* ارتفاع أصغر للهواتف */
    }
}

/* اجعل الصور تتناسب مع حجم الحاوية */
.swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* تأكد من أن الصور تغطي المساحة بشكل مناسب */
}

    </style>
        

<style>
<style>
    /* Testimonial styles */
    .axil-testimonial {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .testimonial-content {
        text-align: center;
    }

    .testimonial-content .description {
        font-size: 1rem;
        font-style: italic;
        color: #666;
        margin-bottom: 10px;
    }

    .testimonial-content .customer-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
    }

    .customer-rating {
        margin-top: 10px;
    }

    .customer-rating i {
        color: #FFD700;
        font-size: 1.2rem;
    }
</style>
</style>

<style>
/* Account Cards */
.account-card {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s ease;
}

.account-card:hover {
    transform: translateY(-5px);
    text-decoration: none;
    color: inherit;
}

.account-box {
    background: #1a1f2e;
    border-radius: 12px;
    overflow: hidden;
    transition: box-shadow 0.2s ease;
    border: 1px solid rgba(255,255,255,0.1);
}

.account-card:hover .account-box {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    border-color: var(--color-primary);
}

.account-image {
    height: 120px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #252b3d 0%, #1a1f2e 100%);
    padding: 15px;
}

.account-image img {
    max-height: 80px;
    max-width: 100%;
    object-fit: contain;
}

.account-placeholder-icon {
    height: 80px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #6c757d;
}

.account-placeholder-icon i {
    font-size: 3rem;
}

.account-info {
    padding: 15px;
    text-align: center;
}

.account-title {
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.account-category {
    display: block;
    font-size: 0.8rem;
    color: #8a8fa3;
    margin-bottom: 8px;
}

.account-price {
    font-size: 1.1rem;
    font-weight: bold;
    color: var(--color-primary);
}

@media (max-width: 767px) {
    .account-image {
        height: 90px;
        padding: 10px;
    }

    .account-image img {
        max-height: 60px;
    }

    .account-placeholder-icon {
        height: 60px;
    }

    .account-placeholder-icon i {
        font-size: 2.5rem;
    }

    .account-info {
        padding: 10px;
    }

    .account-title {
        font-size: 0.9rem;
    }

    .account-price {
        font-size: 1rem;
    }
}
</style>
@endsection

@section('scripts')
<script>
    var swiper = new Swiper('.swiper-container', {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Category Tabs Filter
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            const category = this.getAttribute('data-category');
            const products = document.querySelectorAll('.product-item');

            products.forEach(product => {
                if (category === 'all') {
                    product.classList.remove('hidden');
                } else {
                    if (product.getAttribute('data-category') === category) {
                        product.classList.remove('hidden');
                    } else {
                        product.classList.add('hidden');
                    }
                }
            });
        });
    });
</script>
@endsection
