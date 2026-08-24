<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('تسجيل دخول المديرين | Souq Siarh') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #1A3263;
            --primary-dark: #0f1f3d;
            --gold: #d4a017;
            --gold-light: #FEC303;
            --bg: #f4f6fa;
            --card-bg: #ffffff;
            --input-bg: #f8f9fc;
            --text-dark: #1C1C28;
            --text-muted: #8E92A4;
            --border-color: #ECEEF2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: var(--card-bg);
            padding: 48px 40px;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(26, 50, 99, 0.08);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--gold-light));
        }

        .logo-section {
            text-align: center;
            margin-bottom: 36px;
        }

        .logo-section img {
            height: 52px;
            max-width: 100%;
        }

        .form-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .form-header h1 {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 6px;
            color: var(--text-dark);
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
        }

        .form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1.5px solid var(--border-color);
            padding: 14px {{ app()->getLocale() == 'ar' ? '48px 14px 16px' : '16px 14px 48px' }};
            border-radius: 12px;
            color: var(--text-dark);
            font-size: 15px;
            font-weight: 500;
            transition: all 0.25s;
            outline: none;
        }

        .form-control::placeholder {
            color: #b0b8c4;
            font-weight: 400;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(26, 50, 99, 0.08);
            background: #fff;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 6px 16px rgba(26, 50, 99, 0.2);
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(26, 50, 99, 0.3);
        }

        .btn-login i {
            font-size: 20px;
        }

        .error-message {
            background: #fef6e6;
            color: #b8860b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
            text-align: center;
            border: 1px solid rgba(212, 160, 23, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .error-message i {
            font-size: 16px;
            color: var(--gold);
        }

        .remember-row {
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-row label {
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
            font-weight: 500;
        }

        .footer-text {
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="logo-section">
                <img src="{{ asset('new-store/images/Logo.svg') }}" alt="Souq Siarh">
            </div>

            <div class="form-header">
                <h1>{{ __('لوحة تحكم المديرين') }}</h1>
                <p>{{ __('قم بتسجيل الدخول للمتابعة') }}</p>
            </div>

            @if($errors->any())
                <div class="error-message">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('crm.login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">{{ __('اسم المستخدم') }}</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person"></i>
                        <input type="text" name="username" class="form-control" placeholder="admin" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('كلمة المرور') }}</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">{{ __('تذكرني في المرة القادمة') }}</label>
                </div>

                <button type="submit" class="btn-login">
                    {{ __('تسجيل الدخول') }}
                    <i class="bi bi-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}-short"></i>
                </button>
            </form>

        </div>

        <div class="footer-text">
            &copy; {{ date('Y') }} Souq Siarh Dashboard. All rights reserved.
        </div>
    </div>

</body>
</html>
