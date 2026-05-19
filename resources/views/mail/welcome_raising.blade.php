<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MagnateHub</title>
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
            padding: 50px 40px;
            text-align: center;
        }
        
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 25px;
        }
        
        .header-title {
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 10px 0;
            letter-spacing: -0.5px;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 400;
        }
        
        .email-body {
            padding: 50px 40px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecef 100%);
            border-left: 4px solid #667eea;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .highlight-title {
            font-size: 18px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        .feature-item {
            padding: 12px 0;
            color: #4a5568;
            font-size: 15px;
            display: flex;
            align-items: flex-start;
        }
        
        .feature-icon {
            color: #667eea;
            font-weight: bold;
            margin-right: 12px;
            font-size: 18px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            padding: 25px;
            background-color: #f8f9fa;
            border-radius: 12px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            display: block;
        }
        
        .stat-label {
            font-size: 13px;
            color: #718096;
            margin-top: 5px;
        }
        
        .email-footer {
            background-color: #f8f9fa;
            padding: 35px 40px;
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
            
            .greeting {
                font-size: 20px;
            }
            
            .message {
                font-size: 15px;
            }
            
            .stats-container {
                flex-direction: column;
                gap: 20px;
            }
            
            .cta-button {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="{{ asset('/raising/assets/images/logo/logo.png') }}" alt="MagnateHub Logo" class="logo">
            <h1 class="header-title">Welcome to MagnateHub! 🎉</h1>
            <p class="header-subtitle">Your journey to investment success starts here</p>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Hello {{ $detail['name'] }}! 👋</div>
            
            <p class="message">
                Congratulations on successfully creating your account! We're thrilled to have you join the <strong>MagnateHub</strong> community. 
                You're now part of a premier platform connecting investors with exceptional opportunities.
            </p>
            
            <div class="highlight-box">
                <div class="highlight-title">🎁 Your Free Plan is Active!</div>
                <p class="message" style="margin-bottom: 10px;">
                    We've automatically activated your <strong>Free Plan</strong> with the following benefits:
                </p>
                <ul class="feature-list">
                    <li class="feature-item">
                        <span class="feature-icon">✓</span>
                        <span><strong>5 Project Listings</strong> to showcase your opportunities</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">✓</span>
                        <span><strong>3 Months Access</strong> to explore the platform</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">✓</span>
                        <span><strong>Basic Analytics</strong> to track your performance</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">✓</span>
                        <span><strong>Community Access</strong> to connect with investors</span>
                    </li>
                </ul>
            </div>
            
            <p class="message">
                <strong>Ready to get started?</strong> Here's what you can do next:
            </p>
            
            <ul class="feature-list">
                <li class="feature-item">
                    <span class="feature-icon">1.</span>
                    <span>Complete your profile to build credibility</span>
                </li>
                <li class="feature-item">
                    <span class="feature-icon">2.</span>
                    <span>Create your first project listing</span>
                </li>
                <li class="feature-item">
                    <span class="feature-icon">3.</span>
                    <span>Connect with potential investors</span>
                </li>
                <li class="feature-item">
                    <span class="feature-icon">4.</span>
                    <span>Explore premium plans for enhanced features</span>
                </li>
            </ul>
            
            <center>
                <a href="{{ $detail['dashboard_url'] }}" class="cta-button">Go to Dashboard</a>
            </center>
            
            <div class="stats-container">
                <div class="stat-item">
                    <span class="stat-number">1000+</span>
                    <span class="stat-label">Active Investors</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">$50M+</span>
                    <span class="stat-label">Capital Raised</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Successful Deals</span>
                </div>
            </div>
            
            <p class="message">
                If you have any questions or need assistance, our support team is here to help. 
                Don't hesitate to reach out!
            </p>
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
                © {{ date('Y') }} MagnateHub. All rights reserved.<br>
                You're receiving this email because you created an account on MagnateHub.
            </p>
        </div>
    </div>
</body>
</html>

