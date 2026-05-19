<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Chat Message - MagnateHub</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07), 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 20px;
        }

        .header-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .email-body {
            padding: 40px 32px;
        }

        .greeting {
            font-size: 22px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 18px;
        }

        .message {
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        .highlight-box {
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecef 100%);
            border-left: 4px solid #667eea;
            border-radius: 10px;
            padding: 20px;
            margin: 24px 0;
        }

        .row {
            margin-bottom: 12px;
            font-size: 14px;
            color: #334155;
        }

        .row strong {
            color: #1f2937;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            margin-top: 6px;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 30px 34px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .company-name {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }

        .footer-text {
            font-size: 13px;
            color: #718096;
            margin: 4px 0;
        }

        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 16px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('/raising/assets/images/logo/logo.png') }}" alt="MagnateHub Logo" class="logo">
            <h1 class="header-title">New Chat Message</h1>
        </div>

        <div class="email-body">
            <div class="greeting">Hello {{ $detail['recipient_name'] }}! 👋</div>

            <p class="message">
                You received a new message on MagnateHub.
            </p>

            <div class="highlight-box">
                <div class="row"><strong>From:</strong> {{ $detail['sender_name'] }}</div>
                <div class="row"><strong>Listing:</strong> {{ $detail['listing_name'] }}</div>
                <div class="row"><strong>Message:</strong> {{ $detail['message'] }}</div>
            </div>

            <p class="message">
                Open your chat to reply quickly.
            </p>

            <a href="{{ $detail['chat_url'] }}" class="cta-button">Open Chat</a>
        </div>

        <div class="email-footer">
            <div class="company-name">MagnateHub</div>
            <p class="footer-text">Connecting Investors with Opportunities</p>
            <div class="divider"></div>
            <p class="footer-text">
                Need help? Contact us at
                <a href="mailto:support@magnatehub.au" style="color: #667eea; text-decoration: none;">support@magnatehub.au</a>
            </p>
            <p class="footer-text">&copy; {{ date('Y') }} MagnateHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
