@extends('layouts.web')

@section('title', $account->title)

@section('content')
<main class="main-wrapper">
    <div class="account-details-page" style="margin-top: 100px;">
        <!-- Hero Section -->
        <div class="account-hero">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="account-hero-image">
                            @if($account->category->image)
                                <img src="{{ Storage::url($account->category->image) }}" alt="{{ $account->title }}" class="hero-image">
                            @else
                                <div class="hero-image-placeholder">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                            @endif
                            <div class="category-badge-hero">
                                <i class="fas fa-tag"></i>
                                {{ $account->category->name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-hero-content">
                            <h1 class="account-main-title">{{ $account->title }}</h1>

                            @if($account->description)
                                <p class="account-main-description">{{ $account->description }}</p>
                            @endif

                            <div class="price-section">
                                <span class="price-label">السعر</span>
                                <h2 class="price-amount">{{ number_format($account->price, 2) }} <span class="currency">د.ل</span></h2>
                            </div>

                            @if($telegramUsername)
                                <a href="https://t.me/{{ $telegramUsername }}?text=مرحباً، أريد شراء: {{ $account->title }}"
                                   target="_blank"
                                   class="telegram-contact-btn">
                                    <i class="fab fa-telegram"></i>
                                    <span>تواصل معنا عبر تيليجرام للشراء</span>
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            @else
                                <button class="telegram-contact-btn disabled" disabled>
                                    <i class="fas fa-times"></i>
                                    <span>التواصل غير متوفر حالياً</span>
                                </button>
                            @endif

                            <div class="info-badges">
                                <div class="info-badge">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>حساب مضمون</span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-bolt"></i>
                                    <span>تسليم فوري</span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-headset"></i>
                                    <span>دعم فني</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information Section -->
        <div class="account-info-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="purchase-card">
                            <div class="purchase-header">
                                <i class="fas fa-shopping-cart"></i>
                                <h4>جاهز للشراء؟</h4>
                            </div>
                            <div class="purchase-body">
                                <div class="price-display">
                                    <span class="price-text">السعر النهائي</span>
                                    <h3 class="final-price">{{ number_format($account->price, 2) }} <small>د.ل</small></h3>
                                </div>

                                @if($telegramUsername)
                                    <a href="https://t.me/{{ $telegramUsername }}?text=مرحباً، أريد شراء: {{ $account->title }}%0A%0Aالسعر: {{ number_format($account->price, 2) }} د.ل"
                                       target="_blank"
                                       class="purchase-telegram-btn">
                                        <i class="fab fa-telegram"></i>
                                        <span>اشتري الآن عبر تيليجرام</span>
                                    </a>
                                @endif

                                <div class="purchase-features">
                                    <div class="purchase-feature">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>حماية المشتري</span>
                                    </div>
                                    <div class="purchase-feature">
                                        <i class="fas fa-clock"></i>
                                        <span>تسليم فوري</span>
                                    </div>
                                    <div class="purchase-feature">
                                        <i class="fas fa-redo"></i>
                                        <span>ضمان الاسترجاع</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Accounts Section -->
        @if(count($relatedAccounts) > 0)
        <div class="related-accounts-section">
            <div class="container">
                <h3 class="section-title text-center mb-5">
                    <i class="fas fa-layer-group"></i>
                    حسابات مشابهة
                </h3>
                <div class="row g-4">
                    @foreach($relatedAccounts as $relatedAccount)
                        <div class="col-lg-3 col-md-6">
                            <div class="related-account-card">
                                <a href="/shop/accounts/{{ $relatedAccount->id }}">
                                    @if($relatedAccount->category->image)
                                        <img src="{{ Storage::url($relatedAccount->category->image) }}" alt="{{ $relatedAccount->title }}" class="related-image">
                                    @else
                                        <div class="related-image-placeholder">
                                            <i class="fas fa-user-circle fa-3x"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</main>
@endsection

@section('styles')
<style>
    .account-details-page {
        padding-bottom: 60px;
    }

    /* Hero Section */
    .account-hero {
        background-color: var(--dark-bg-secondary);
        padding: 80px 0;
        color: var(--text-primary);
    }

    .account-hero-image {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--dark-border);
    }

    .hero-image {
        width: 100%;
        height: 450px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .account-hero-image:hover .hero-image {
        transform: scale(1.05);
    }

    .hero-image-placeholder {
        width: 100%;
        height: 450px;
        background-color: var(--dark-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 140px;
        color: var(--gold-primary);
    }

    .category-badge-hero {
        position: absolute;
        top: 25px;
        right: 25px;
        background-color: var(--gold-primary);
        color: var(--dark-bg);
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
    }

    .account-hero-content {
        padding: 20px;
    }

    .account-main-title {
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 25px;
        line-height: 1.2;
        color: var(--text-primary);
    }

    .account-main-description {
        font-size: 1.3rem;
        line-height: 1.9;
        margin-bottom: 35px;
        color: var(--text-secondary);
    }

    .price-section {
        margin-bottom: 35px;
    }

    .price-label {
        display: block;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 12px;
        color: var(--gold-primary);
        font-weight: 700;
    }

    .price-amount {
        font-size: 4rem;
        font-weight: 900;
        margin: 0;
        color: var(--gold-primary);
    }

    .currency {
        font-size: 1.8rem;
    }

    .telegram-contact-btn {
        display: inline-flex;
        align-items: center;
        gap: 18px;
        padding: 25px 50px;
        background-color: var(--gold-primary);
        color: var(--dark-bg);
        border: none;
        border-radius: 12px;
        font-size: 1.5rem;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 8px 24px rgba(201, 166, 54, 0.3);
        transition: all 0.3s ease;
        margin-bottom: 35px;
    }

    .telegram-contact-btn:hover {
        background-color: var(--gold-secondary);
        box-shadow: 0 12px 32px rgba(201, 166, 54, 0.5);
        color: var(--dark-bg);
    }

    .telegram-contact-btn.disabled {
        background-color: var(--dark-bg-card);
        color: var(--text-muted);
        cursor: not-allowed;
    }

    .telegram-contact-btn i:first-child {
        font-size: 2.2rem;
    }

    .telegram-contact-btn i:last-child {
        font-size: 1.4rem;
    }

    .info-badges {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
    }

    .info-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 24px;
        background-color: var(--dark-bg-card);
        border: 1px solid var(--dark-border);
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 700;
        color: var(--text-primary);
    }

    .info-badge:hover {
        border-color: var(--gold-primary);
    }

    .info-badge i {
        font-size: 1.4rem;
        color: var(--gold-primary);
    }

    /* Account Info Section */
    .account-info-section {
        padding: 80px 0;
    }

    .purchase-card {
        background-color: var(--dark-bg-card);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--dark-border);
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }

    .purchase-card:hover {
        border-color: var(--gold-primary);
        box-shadow: 0 8px 24px rgba(201, 166, 54, 0.15);
    }

    .purchase-header {
        background-color: var(--dark-bg-secondary);
        color: var(--text-primary);
        padding: 30px;
        text-align: center;
    }

    .purchase-header i {
        font-size: 3rem;
        margin-bottom: 12px;
        color: var(--gold-primary);
    }

    .purchase-header h4 {
        margin: 0;
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .purchase-body {
        padding: 35px;
    }

    .price-display {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--dark-border);
    }

    .price-text {
        display: block;
        font-size: 0.95rem;
        color: var(--text-muted);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
    }

    .final-price {
        font-size: 3rem;
        font-weight: 900;
        color: var(--gold-primary);
        margin: 0;
    }

    .final-price small {
        font-size: 1.5rem;
    }

    .purchase-telegram-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        width: 100%;
        padding: 22px;
        background-color: var(--gold-primary);
        color: var(--dark-bg);
        border: none;
        border-radius: 12px;
        font-size: 1.3rem;
        font-weight: 800;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-bottom: 30px;
    }

    .purchase-telegram-btn:hover {
        background-color: var(--gold-secondary);
        color: var(--dark-bg);
    }

    .purchase-features {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .purchase-feature {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .purchase-feature:hover {
        color: var(--text-primary);
    }

    .purchase-feature i {
        color: var(--gold-primary);
        font-size: 1.3rem;
    }

    /* Related Accounts Section */
    .related-accounts-section {
        padding: 80px 0;
    }

    .related-accounts-section .section-title {
        color: var(--text-primary);
        font-size: 2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .related-accounts-section .section-title i {
        color: var(--gold-primary);
        font-size: 2.2rem;
    }

    .related-account-card {
        background-color: var(--dark-bg-card);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--dark-border);
        transition: all 0.3s ease;
    }

    .related-account-card:hover {
        border-color: var(--gold-primary);
        box-shadow: 0 8px 24px rgba(201, 166, 54, 0.15);
    }

    .related-account-card a {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .related-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .related-account-card:hover .related-image {
        transform: scale(1.05);
    }

    .related-image-placeholder {
        width: 100%;
        height: 220px;
        background-color: var(--dark-bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-primary);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .account-main-title {
            font-size: 2rem;
        }

        .price-amount {
            font-size: 2.5rem;
        }

        .telegram-contact-btn {
            font-size: 1.1rem;
            padding: 15px 30px;
        }
    }
</style>
@endsection
