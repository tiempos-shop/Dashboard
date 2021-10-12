<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmacionMail extends Mailable
{
    use Queueable, SerializesModels;

    

    public $info;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datosInfo)
    {
        $this->info = $datosInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.confirmacion');
    }
}
