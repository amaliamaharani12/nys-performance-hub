<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - Login</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, "Segoe UI", Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 36px 32px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .login-box img {
            display: block;
            height: 56px;
            object-fit: contain;
            margin: 0 auto 20px;
        }
        .login-box h1 {
            font-size: 20px;
            text-align: center;
            margin: 0 0 24px;
            font-weight: 800;
            background: linear-gradient(90deg, #1d4ed8, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .field input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }
        .field input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .error-text {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }
        .status-text {
            background: #dcfce7;
            color: #16a34a;
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .submit-btn {
            width: 100%;
            padding: 11px;
            background: linear-gradient(90deg, #1d4ed8, #3b82f6);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
        }
        .submit-btn:hover { opacity: 0.92; }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
        }
        .back-link:hover { color: #3b82f6; }
    </style>
</head>
<body>

    <div class="login-box">
        <img src="{{ asset('img/logo-pemi.png') }}" alt="Logo">
        <h1>Login {{ config('app.name') }}</h1>

        @if (session('status'))
            <div class="status-text">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="submit-btn">Sign In</button>
        </form>

        <a href="{{ route('progress-achievement') }}" class="back-link">&larr; Back to Public View</a>
    </div>

</body>
</html>