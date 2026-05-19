<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class chat_message_notification extends Mailable
{
    use Queueable, SerializesModels;

    public $detail;

    public function __construct($detail)
    {
        $this->detail = $detail;
    }

    public function build()
    {
        return $this->from('notifications@magnatehub.au', 'MagnateHub Team')
            ->view('mail.chat_message_notification')
            ->subject('New chat message on '.$this->detail['listing_name'])
            ->with('detail', $this->detail);
    }
}
