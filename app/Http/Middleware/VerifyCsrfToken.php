<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'SaveContactForm',
        'SaveNewsLetterForm',
        'RegisterNewUser',
        'WebsiteSignup/SendCode',
        'WebsiteSignup',
        'Raising/Login',
        'Raising/Check/Otp',
        'Raising/Resend/Otp',
        'Raising/Register',
        'GetAllBlogs/comment',
        'stripe',
        'GetStripeToken',
        'dashboard/professionals/chat',
        'Raising/Find',
        'Raising/Forgot/Check/Otp',
        'Raising/Forgot/Change/Password',
    ];
}
