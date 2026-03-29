<?php

namespace App\Mail;

use App\Models\Preorder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPreorderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Preorder $preorder;

    public function __construct(Preorder $preorder)
    {
        $this->preorder = $preorder;
    }

    public function build()
    {
        $mail = $this->subject('New Preorder Inquiry')
            ->view('emails.new_preorder')
            ->with([
                'preorder' => $this->preorder,
            ]);

        if (!empty($this->preorder->file_path)) {
            $fullPath = storage_path('app/public/' . $this->preorder->file_path);

            if (file_exists($fullPath)) {
                $mail->attach($fullPath);
            }
        }

        return $mail;
    }
}
