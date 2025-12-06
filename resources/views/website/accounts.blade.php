@extends('layouts.web')

@section('title', 'حسابات للبيع')

@section('content')
<main class="main-wrapper">
    <div class="axil-shop-area axil-section-gap">
        <div class="container">
            <div class="section-title-wrapper text-center mb-5">
                <h2 class="title display-4 fw-bold">حسابات للبيع</h2>
                <p class="lead">اختر من مجموعة واسعة من الحسابات المميزة</p>
            </div>

            <!-- Category Filter -->
            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="category-filter-wrapper">
                        <a href="/shop/accounts" class="category-filter-btn {{ !isset($category) ? 'active' : '' }}">
                            <i class="fas fa-th"></i>
                            <span>الكل</span>
                        </a>
                        @foreach($categories as $cat)
                            <a href="/shop/accounts/category/{{ $cat->id }}"
                               class="category-filter-btn {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                                <span>{{ $cat->name }}</span>
                                @if($cat->available_accounts_count > 0)
                                    <span class="pill-badge">{{ $cat->available_accounts_count }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Accounts Grid -->
            <div class="row g-4">
                @forelse($accounts as $account)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="account-card">
                            <a href="/shop/accounts/{{ $account->id }}" class="account-card-link">
                                <div class="account-image-wrapper">
                                    @if($account->category->image)
                                        <img src="{{ Storage::url($account->category->image) }}" class="account-image" alt="{{ $account->title }}">
                                    @else
                                        <div class="account-image-placeholder">
                                            <i class="fas fa-user-circle fa-5x"></i>
                                        </div>
                                    @endif
                                    <div class="account-overlay">
                                        <i class="fas fa-eye"></i>
                                        <span>عرض التفاصيل</span>
                                    </div>
                                </div>

                                <div class="account-card-body">
                                    <div class="account-category-badge">
                                        <i class="fas fa-tag"></i>
                                        {{ $account->category->name }}
                                    </div>

                                    <h5 class="account-title">{{ $account->title }}</h5>

                                    @if($account->description)
                                        <p class="account-description">
                                            {{ Str::limit($account->description, 60) }}
                                        </p>
                                    @endif

                                    <div class="account-footer">
                                        <div class="account-price">
                                            <span class="price-label">السعر</span>
                                            <span class="price-value">{{ number_format($account->price, 2) }} <small>د.ل</small></span>
                                        </div>
                                        <div class="account-action">
                                            @if($telegramUsername)
                                                <span class="telegram-btn">
                                                    <i class="fab fa-telegram"></i> تواصل
                                                </span>
                                            @else
                                                <span class="telegram-btn disabled">
                                                    <i class="fas fa-times"></i> غير متوفر
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-inbox fa-5x mb-4"></i>
                            <h3>لا توجد حسابات متاحة حالياً</h3>
                            <p>تحقق مرة أخرى قريباً أو تصفح الفئات الأخرى</p>
                            <a href="/shop/accounts" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-right"></i> عرض جميع الفئات
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($accounts->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="pagination-wrapper">
                            {{ $accounts->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

@section('styles')
<style>
    /* Section Title */
    .section-title-wrapper .subtitle-badge {
        display: inline-block;
        padding: 10px 25px;
        background-color: var(--gold-primary);
        color: var(--dark-bg);
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .section-title-wrapper .title {
        color: var(--text-primary) !important;
        margin-bottom: 15px;
    }

    .section-title-wrapper .lead {
        color: var(--text-secondary) !important;
    }

    /* Category Filter */
    .category-filter-wrapper {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: center;
        padding: 25px;
        background-color: var(--dark-bg-card);
        border-radius: 12px;
        border: 1px solid var(--dark-border);
    }

    .category-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background-color: var(--dark-bg-secondary);
        border: 1px solid var(--dark-border);
        border-radius: 8px;
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .category-filter-btn:hover {
        background-color: var(--gold-primary);
        border-color: var(--gold-primary);
        color: var(--dark-bg);
    }

    .category-filter-btn.active {
        background-color: var(--gold-primary);
        color: var(--dark-bg);
        border-color: var(--gold-primary);
    }

    .pill-badge {
        background-color: var(--dark-bg);
        color: var(--gold-primary);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .category-filter-btn.active .pill-badge {
        background-color: var(--dark-bg);
        color: var(--gold-primary);
    }

    /* Account Cards */
    .account-card {
        background-color: var(--dark-bg-card);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--dark-border);
        transition: all 0.3s ease;
        height: 100%;
    }

    .account-card:hover {
        border-color: var(--gold-primary);
        box-shadow: 0 8px 24px rgba(201, 166, 54, 0.15);
    }

    .account-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .account-image-wrapper {
        position: relative;
        height: 240px;
        overflow: hidden;
        background-color: var(--dark-bg-secondary);
    }

    .account-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .account-card:hover .account-image {
        transform: scale(1.05);
    }

    .account-image-placeholder {
        width: 100%;
        height: 100%;
        background-color: var(--dark-bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-primary);
    }

    .account-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(10, 14, 26, 0.92);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 15px;
        color: var(--gold-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .account-overlay i {
        font-size: 3rem;
    }

    .account-card:hover .account-overlay {
        opacity: 1;
    }

    .account-card-body {
        padding: 25px;
    }

    .account-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background-color: var(--dark-bg-secondary);
        color: var(--gold-primary);
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 15px;
        border: 1px solid var(--dark-border);
    }

    .account-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 12px;
        min-height: 60px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .account-description {
        font-size: 0.95rem;
        color: var(--text-secondary);
        margin-bottom: 18px;
        min-height: 45px;
        line-height: 1.6;
    }

    .account-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 18px;
        border-top: 1px solid var(--dark-border);
    }

    .account-price {
        display: flex;
        flex-direction: column;
    }

    .price-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .price-value {
        font-size: 1.6rem;
        font-weight: 900;
        color: var(--gold-primary);
    }

    .price-value small {
        font-size: 1rem;
    }

    .telegram-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background-color: var(--gold-primary);
        color: var(--dark-bg);
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .telegram-btn:hover {
        background-color: var(--gold-secondary);
    }

    .telegram-btn.disabled {
        background-color: var(--dark-bg-secondary);
        color: var(--text-muted);
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 100px 20px;
        background-color: var(--dark-bg-card);
        border-radius: 12px;
        border: 1px solid var(--dark-border);
    }

    .empty-state i {
        color: var(--text-muted);
    }

    .empty-state h3 {
        color: var(--text-primary);
        font-weight: 700;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: var(--text-secondary);
    }

    .empty-state .btn-primary {
        background-color: var(--gold-primary);
        color: var(--dark-bg);
        border: none;
        font-weight: 700;
        padding: 12px 30px;
    }

    .empty-state .btn-primary:hover {
        background-color: var(--gold-secondary);
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        padding: 25px;
        background-color: var(--dark-bg-card);
        border-radius: 12px;
        border: 1px solid var(--dark-border);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .account-title {
            font-size: 1.05rem;
            min-height: 50px;
        }

        .account-image-wrapper {
            height: 200px;
        }

        .price-value {
            font-size: 1.3rem;
        }

        .telegram-btn {
            padding: 10px 16px;
            font-size: 0.85rem;
        }

        .category-filter-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection
