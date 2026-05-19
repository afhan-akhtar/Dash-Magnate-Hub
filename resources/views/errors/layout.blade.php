<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MagnateHub error page">
    <title>{{ config('app.name', 'MagnateHub') }} | {{ $title ?? 'Error' }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(67, 97, 238, 0.14), transparent 32%),
                radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.12), transparent 28%),
                #f8fafc;
        }
        .error-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }
        .error-card {
            width: min(720px, 100%);
            border: 0;
            border-radius: 28px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }
        .error-status {
            font-size: 88px;
            line-height: 1;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.04em;
        }
        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(67, 97, 238, 0.12);
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
    </style>
</head>
<body>
    <div class="error-shell">
        <div class="card error-card">
            <div class="card-body p-4 p-md-5">
                <div class="error-badge mb-4">
                    <i class="feather-alert-circle"></i>
                    <span>MagnateHub</span>
                </div>
                <div class="error-status">{{ $status ?? 500 }}</div>
                <h1 class="h2 text-dark mt-3 mb-3">{{ $title ?? 'Unexpected Error' }}</h1>
                <p class="text-muted fs-15 mb-4">{{ $message ?? 'Something went wrong while loading this page.' }}</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-light-brand">Go Back</a>
                    <a href="{{ url('/') }}" class="btn btn-primary">Open Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
