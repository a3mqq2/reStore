@extends('layouts.web')

@section('title', 'انشاء حساب جديد')

@section('content')
<div class="register-page-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="register-card">
                    <div class="register-header">
                        <i class="fas fa-user-plus"></i>
                        <h2>تسجيل حساب جديد</h2>
                        <p>انضم إلينا وابدأ رحلتك معنا</p>
                    </div>

                    <div class="register-body">
                        <form action="{{route('website.register-store')}}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="form-group-dark">
                                <label for="name" class="form-label-dark">
                                    <i class="fas fa-user"></i>
                                    الاسم الكامل
                                </label>
                                <input id="name"
                                       type="text"
                                       class="form-input-dark @error('name') is-invalid @enderror"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="أدخل اسمك الكامل"
                                       required
                                       autocomplete="name"
                                       autofocus>
                                @error('name')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group-dark">
                                <label for="phone" class="form-label-dark">
                                    <i class="fas fa-phone"></i>
                                    رقم الهاتف
                                </label>
                                <input id="phone"
                                       type="tel"
                                       class="form-input-dark @error('phone') is-invalid @enderror"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       placeholder="مثال: +966501234567"
                                       required
                                       autocomplete="tel">
                                @error('phone')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group-dark">
                                <label for="email" class="form-label-dark">
                                    <i class="fas fa-envelope"></i>
                                    البريد الإلكتروني
                                </label>
                                <input id="email"
                                       type="email"
                                       class="form-input-dark @error('email') is-invalid @enderror"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="example@email.com"
                                       required
                                       autocomplete="email">
                                @error('email')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group-dark">
                                <label for="city" class="form-label-dark">
                                    <i class="fas fa-map-marker-alt"></i>
                                    المدينة
                                </label>
                                <select id="city"
                                        class="form-input-dark @error('city') is-invalid @enderror"
                                        name="city_id"
                                        required>
                                    <option value="">اختر المدينة</option>
                                    @foreach ($cities as $city)
                                        <option value="{{$city->id}}">{{$city->name}}</option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group-dark">
                                <label for="password" class="form-label-dark">
                                    <i class="fas fa-lock"></i>
                                    كلمة المرور
                                </label>
                                <input id="password"
                                       type="password"
                                       class="form-input-dark @error('password') is-invalid @enderror"
                                       name="password"
                                       placeholder="أدخل كلمة مرور قوية"
                                       required
                                       autocomplete="new-password">
                                @error('password')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group-dark">
                                <label for="password-confirm" class="form-label-dark">
                                    <i class="fas fa-lock"></i>
                                    تأكيد كلمة المرور
                                </label>
                                <input id="password-confirm"
                                       type="password"
                                       class="form-input-dark"
                                       name="password_confirmation"
                                       placeholder="أعد إدخال كلمة المرور"
                                       required
                                       autocomplete="new-password">
                            </div>

                            <button type="submit" class="btn-register-dark">
                                <i class="fas fa-user-check"></i>
                                <span>إنشاء حساب جديد</span>
                            </button>

                            <div class="register-footer">
                                <p>لديك حساب بالفعل؟ <a href="{{ route('website.login') }}">تسجيل الدخول</a></p>
                            </div>

                            {{-- <div class="divider-dark">
                                <span>أو</span>
                            </div>

                            <a href="{{ route('google.redirect') }}" class="btn-google-dark">
                                <i class="fab fa-google"></i>
                                <span>التسجيل عبر جوجل</span>
                            </a> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.register-page-wrapper {
    min-height: 100vh;
    padding: 100px 0 60px;
    background-color: var(--dark-bg);
}

.register-card {
    background-color: var(--dark-bg-card);
    border: 1px solid var(--dark-border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.register-header {
    background-color: var(--dark-bg-secondary);
    padding: 40px 30px;
    text-align: center;
    border-bottom: 1px solid var(--dark-border);
}

.register-header i {
    font-size: 48px;
    color: var(--gold-primary);
    margin-bottom: 15px;
}

.register-header h2 {
    color: var(--text-primary);
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 10px;
}

.register-header p {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0;
}

.register-body {
    padding: 40px 30px;
}

.form-group-dark {
    margin-bottom: 25px;
}

.form-label-dark {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 10px;
}

.form-label-dark i {
    color: var(--gold-primary);
    font-size: 16px;
}

.form-input-dark {
    width: 100%;
    padding: 14px 16px;
    background-color: var(--dark-bg-secondary);
    border: 1px solid var(--dark-border);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 15px;
    transition: all 0.3s ease;
}

.form-input-dark::placeholder {
    color: var(--text-muted);
}

.form-input-dark:focus {
    outline: none;
    border-color: var(--gold-primary);
    background-color: var(--dark-bg-card);
    box-shadow: 0 0 0 3px rgba(201, 166, 54, 0.1);
}

.form-input-dark.is-invalid {
    border-color: #ef4444;
}

.error-message {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #ef4444;
    font-size: 13px;
    margin-top: 8px;
}

.error-message i {
    font-size: 14px;
}

.btn-register-dark {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px;
    background-color: var(--gold-primary);
    color: var(--dark-bg);
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 30px;
}

.btn-register-dark:hover {
    background-color: var(--gold-secondary);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(201, 166, 54, 0.3);
}

.btn-register-dark i {
    font-size: 20px;
}

.register-footer {
    text-align: center;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid var(--dark-border);
}

.register-footer p {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0;
}

.register-footer a {
    color: var(--gold-primary);
    font-weight: 700;
    text-decoration: none;
    transition: color 0.3s ease;
}

.register-footer a:hover {
    color: var(--gold-secondary);
    text-decoration: underline;
}

.divider-dark {
    position: relative;
    text-align: center;
    margin: 30px 0;
}

.divider-dark::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background-color: var(--dark-border);
}

.divider-dark span {
    position: relative;
    padding: 0 15px;
    background-color: var(--dark-bg-card);
    color: var(--text-muted);
    font-size: 14px;
}

.btn-google-dark {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px;
    background-color: var(--dark-bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--dark-border);
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-google-dark:hover {
    background-color: var(--dark-bg);
    border-color: var(--gold-primary);
    color: var(--text-primary);
}

.btn-google-dark i {
    font-size: 18px;
}

/* Select Dropdown Styling */
select.form-input-dark {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23c9a636' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 16px center;
    padding-left: 40px;
}

select.form-input-dark option {
    background-color: var(--dark-bg-secondary);
    color: var(--text-primary);
    padding: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .register-page-wrapper {
        padding: 80px 15px 40px;
    }

    .register-header {
        padding: 30px 20px;
    }

    .register-header h2 {
        font-size: 24px;
    }

    .register-header i {
        font-size: 40px;
    }

    .register-body {
        padding: 30px 20px;
    }

    .form-input-dark {
        padding: 12px 14px;
        font-size: 14px;
    }

    .btn-register-dark {
        padding: 14px;
        font-size: 16px;
    }
}
</style>
@endsection
