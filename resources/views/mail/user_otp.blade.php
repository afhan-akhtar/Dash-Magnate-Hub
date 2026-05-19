<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - MagnateHub</title>
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
            padding: 50px 40px;
            text-align: center;
        }
        
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 35px;
            line-height: 1.7;
        }
        
        .otp-container {
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecef 100%);
            border: 2px dashed #667eea;
            border-radius: 12px;
            padding: 30px;
            margin: 35px 0;
            display: inline-block;
        }
        
        .otp-label {
            font-size: 13px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .otp-code {
            font-size: 42px;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        
        .validity-note {
            font-size: 14px;
            color: #718096;
            margin-top: 30px;
            padding: 15px;
            background-color: #fef5e7;
            border-left: 4px solid #f39c12;
            border-radius: 6px;
            text-align: left;
        }
        
        .validity-note strong {
            color: #d68910;
        }
        
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .footer-text {
            font-size: 13px;
            color: #718096;
            margin: 5px 0;
        }
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 20px 0;
        }
        
        .security-note {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 15px;
            line-height: 1.5;
        }
        
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            
            .email-header {
                padding: 30px 20px;
            }
            
            .otp-code {
                font-size: 36px;
                letter-spacing: 6px;
            }
            
            .greeting {
                font-size: 18px;
            }
            
            .message {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="{{ asset('/raising/assets/images/logo/logo.png') }}" alt="MagnateHub Logo" class="logo">
            <h1 class="header-title">Email Verification</h1>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Welcome to MagnateHub! 🎉</div>
            
            <p class="message">
                Thank you for creating an account with <strong>MagnateHub</strong>. To verify your email address 
                and complete your registration, please use the verification code below:
            </p>
            
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ $detail['otp'] }}</div>
            </div>
            
            <p class="message">
                Enter this code on the verification page to activate your account and start exploring 
                exciting investment opportunities.
            </p>
            
            <div class="validity-note">
                <strong>⏱ Important:</strong> This verification code is valid for 24 hours. 
                If you didn't create an account with MagnateHub, please ignore this email.
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="company-name">MagnateHub</div>
            <p class="footer-text">Connecting Investors with Opportunities</p>
            
            <div class="divider"></div>
            
            <p class="footer-text">
                Need help? Contact us at <a href="mailto:support@magnatehub.au" style="color: #667eea; text-decoration: none;">support@magnatehub.au</a>
            </p>
            
            <p class="security-note">
                This is an automated message. Please do not reply to this email.<br>
                © {{ date('Y') }} MagnateHub. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
