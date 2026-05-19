<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Subscriber;

class ContactService
{
    public function submitContactForm(array $data): Contact
    {
        return Contact::create([
            'type'    => 'contact',
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'code'    => md5(uniqid()),
        ]);
    }

    public function submitNewsletterSubscription(string $email): Subscriber
    {
        return Subscriber::firstOrCreate(
            ['email' => $email],
            ['code' => md5(uniqid())]
        );
    }
}
