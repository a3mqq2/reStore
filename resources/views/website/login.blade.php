@extends('layouts.web')

@section('title', 'تسجيل دخول')

@section('content')
<div class="login-page-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-sign-in-alt"></i>
                        <h2>تسجيل الدخول</h2>
                        <p>مرحباً بعودتك! سجل دخولك للمتابعة</p>
                    </div>

                    <div class="login-body">
                        <form action="{{route('website.login')}}" id="form-login" method="POST">
                            @csrf
                            @method('POST')

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
                                <label for="password" class="form-label-dark">
                                    <i class="fas fa-lock"></i>
                                    كلمة المرور
                                </label>
                                <input id="password"
                                       type="password"
                                       class="form-input-dark @error('password') is-invalid @enderror"
                                       name="password"
                                       placeholder="أدخل كلمة المرور"
                                       required
                                       autocomplete="current-password">
                                @error('password')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="login-options">
                                <div class="remember-me">
                                    <input type="checkbox"
                                           name="remember"
                                           id="remember"
                                           class="checkbox-dark"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember" class="checkbox-label">تذكرني</label>
                                </div>
                                <a href="{{route('website.reset')}}" class="forgot-password">
                                    نسيت كلمة المرور؟
                                </a>
                            </div>

                            <button type="submit" id="submit-button" class="btn-login-dark">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>تسجيل الدخول</span>
                            </button>

                            <div class="login-footer">
                                <p>ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
                            </div>

                            {{-- <div class="divider-dark">
                                <span>أو</span>
                            </div>

                            <a href="{{ route('google.redirect') }}" class="btn-google-dark">
                                <i class="fab fa-google"></i>
                                <span>تسجيل الدخول عبر جوجل</span>
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
.login-page-wrapper {
    min-height: 100vh;
    padding: 100px 0 60px;
    background-color: var(--dark-bg);
}

.login-card {
    background-color: var(--dark-bg-card);
    border: 1px solid var(--dark-border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.login-header {
    background-color: var(--dark-bg-secondary);
    padding: 40px 30px;
    text-align: center;
    border-bottom: 1px solid var(--dark-border);
}

.login-header i {
    font-size: 48px;
    color: var(--gold-primary);
    margin-bottom: 15px;
}

.login-header h2 {
    color: var(--text-primary);
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 10px;
}

.login-header p {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0;
}

.login-body {
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

.login-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
}

.checkbox-dark {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--gold-primary);
}

.checkbox-label {
    color: var(--text-secondary);
    font-size: 14px;
    cursor: pointer;
    margin: 0;
}

.forgot-password {
    color: var(--gold-primary);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}

.forgot-password:hover {
    color: var(--gold-secondary);
    text-decoration: underline;
}

.btn-login-dark {
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
}

.btn-login-dark:hover:not(:disabled) {
    background-color: var(--gold-secondary);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(201, 166, 54, 0.3);
}

.btn-login-dark:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-login-dark i {
    font-size: 20px;
}

.login-footer {
    text-align: center;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid var(--dark-border);
}

.login-footer p {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0;
}

.login-footer a {
    color: var(--gold-primary);
    font-weight: 700;
    text-decoration: none;
    transition: color 0.3s ease;
}

.login-footer a:hover {
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

/* Responsive */
@media (max-width: 768px) {
    .login-page-wrapper {
        padding: 80px 15px 40px;
    }

    .login-header {
        padding: 30px 20px;
    }

    .login-header h2 {
        font-size: 24px;
    }

    .login-header i {
        font-size: 40px;
    }

    .login-body {
        padding: 30px 20px;
    }

    .form-input-dark {
        padding: 12px 14px;
        font-size: 14px;
    }

    .btn-login-dark {
        padding: 14px;
        font-size: 16px;
    }

    .login-options {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
}
</style>
@endsection

@section('scripts')
<script>
    document.getElementById('form-login').addEventListener('submit', function() {
        document.getElementById('submit-button').disabled = true;
    });
</script>
@endsection
