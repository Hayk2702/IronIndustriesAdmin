<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $email,
        public string $fullname,
        public ?string $phone,
        public string $messageText,
        public ?UploadedFile $file = null,
    ) {}

    public function build()
    {
        $mail = $this->subject('New contact message: ' . $this->fullname)
            ->replyTo($this->email, $this->fullname)
            ->view('emails.contact-message')
            ->with([
                'email' => $this->email,
                'fullname' => $this->fullname,
                'phone' => $this->phone,
                'messageText' => $this->messageText,
            ]);

        // attach file if exists
        if ($this->file) {
            $mail->attach(
                $this->file->getRealPath(),
                [
                    'as' => $this->file->getClientOriginalName(),
                    'mime' => $this->file->getMimeType(),
                ]
            );
        }

        return $mail;
    }
}
