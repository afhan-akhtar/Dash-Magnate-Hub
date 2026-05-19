<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmation - MagnateHub</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        
        .success-badge {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
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
        
        .plan-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            color: #ffffff;
            text-align: center;
        }
        
        .plan-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .plan-price {
            font-size: 20px;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .plan-details {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 500;
            opacity: 0.9;
        }
        
        .detail-value {
            font-weight: 600;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }
        
        .feature-item {
            padding: 12px 0;
            color: #4a5568;
            font-size: 15px;
            display: flex;
            align-items: flex-start;
        }
        
        .feature-icon {
            color: #10b981;
            font-weight: bold;
            margin-right: 12px;
            font-size: 18px;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-left: 4px solid #10b981;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .highlight-title {
            font-size: 18px;
            font-weight: 600;
            color: #059669;
            margin-bottom: 15px;
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
            
            .plan-card {
                padding: 20px;
            }
            
            .plan-name {
                font-size: 24px;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="{{ asset('/raising/assets/images/logo/logo.png') }}" alt="MagnateHub Logo" class="logo">
            <h1 class="header-title">Subscription Confirmed! ✓</h1>
            <p class="header-subtitle">Thank you for upgrading your account</p>
            <div class="success-badge">✓ Payment Successful</div>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Hello {{ $detail['name'] }}! 🎉</div>
            
            <p class="message">
                Great news! Your subscription to the <strong>{{ $detail['plan_name'] }}</strong> plan has been successfully activated. 
                You now have access to all premium features and benefits.
            </p>
            
            <!-- Plan Card -->
            <div class="plan-card">
                <div class="plan-name">{{ $detail['plan_name'] }} Plan</div>
                @if(isset($detail['price']))
                <div class="plan-price">${{ $detail['price'] }} USD</div>
                @endif
                
                <div class="plan-details">
                    <div class="detail-row">
                        <span class="detail-label">Subscription Date</span>
                        <span class="detail-value">{{ $detail['date'] }}</span>
                    </div>
                    @if(isset($detail['expiry']))
                    <div class="detail-row">
                        <span class="detail-label">Valid Until</span>
                        <span class="detail-value">{{ $detail['expiry'] }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">✓ Active</span>
                    </div>
                </div>
            </div>
            
            <div class="highlight-box">
                <div class="highlight-title">🚀 Your New Features</div>
                <ul class="feature-list">
                    @if($detail['plan_id'] == 1)
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Unlimited Listings</strong> - Post as many projects as you need</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>6 Months Access</strong> - Extended platform access</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Priority Support</strong> - Get help when you need it</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Advanced Analytics</strong> - Track your performance</span>
                        </li>
                    @elseif($detail['plan_id'] == 2)
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Unlimited Listings</strong> - No restrictions on projects</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Featured Placement</strong> - Stand out from the crowd</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Premium Support</strong> - Dedicated assistance</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Advanced Analytics</strong> - Detailed insights</span>
                        </li>
                    @elseif($detail['plan_id'] == 3)
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Unlimited Everything</strong> - No limits on any features</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Priority Placement</strong> - Always featured first</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>White-Glove Support</strong> - Personal account manager</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Custom Branding</strong> - Personalized experience</span>
                        </li>
                    @elseif($detail['plan_id'] == 4)
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Capital Raise Tools</strong> - Specialized features</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Investor Matching</strong> - Connect with the right investors</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Priority Support</strong> - Expert guidance</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Advanced Analytics</strong> - Track your raise progress</span>
                        </li>
                    @else
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Full Platform Access</strong> - All features unlocked</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Premium Support</strong> - Priority assistance</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span><strong>Advanced Features</strong> - Enhanced capabilities</span>
                        </li>
                    @endif
                </ul>
            </div>
            
            <p class="message">
                <strong>Ready to make the most of your subscription?</strong> Head to your dashboard and start leveraging all the premium features available to you.
            </p>
            
            <center>
                <a href="{{ $detail['dashboard_url'] }}" class="cta-button">Go to Dashboard</a>
            </center>
            
            <p class="message">
                If you have any questions about your subscription or need assistance with any features, 
                our support team is here to help you succeed.
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
                This is a confirmation of your subscription purchase.
            </p>
        </div>
    </div>
</body>
</html>

