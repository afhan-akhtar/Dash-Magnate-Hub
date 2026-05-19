<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Login - {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('website/images/black.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            font-family: "Poppins", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background-color: #1a1a2e;
            background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), url("https://magnatehub.au/assets/img/banner/banner.png");
            background-size: cover;
            background-position: center;
        }
        .card {
            width: min(560px, 100%);
            padding: 42px;
            border-radius: 24px;
            color: #fff;
            background: linear-gradient(180deg, rgba(66, 70, 92, 0.92) 0%, rgba(39, 37, 63, 0.94) 100%);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        h1 {
            font-size: 38px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        p {
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 24px;
        }
        .field { margin-bottom: 18px; }
        .input {
            width: 100%;
            height: 58px;
            border: 0;
            border-radius: 8px;
            padding: 0 18px;
            font-size: 16px;
        }
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin: 8px 0 20px;
        }
        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            color: #fff;
        }
        .forgot-link {
            color: rgba(255, 255, 255, 0.92);
            font-size: 14px;
            font-weight: 500;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        .forgot-link:hover {
            color: #fff;
        }
        .btn {
            width: 100%;
            height: 58px;
            border: 0;
            border-radius: 8px;
            background: #5b1dff;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }
        .alert {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 8px;
            background: rgba(220, 53, 69, 0.16);
            border: 1px solid rgba(220, 53, 69, 0.45);
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Professional Login</h1>
        <p>Use your buyer, seller, capital raiser, or broker account to access the dashboard.</p>

        @if (isset($errors) && $errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('professional.login.post') }}">
            @csrf
            <div class="field">
                <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" required autofocus>
            </div>
            <div class="field">
                <input class="input" type="password" name="password" placeholder="Password" required>
            </div>
            <div class="remember-row">
                <label class="remember">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
                <a class="forgot-link" href="https://magnatehub.au/forgot-password">Forgot password?</a>
            </div>
            <button class="btn" type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>
