<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AssociationVerified extends Mailable
{
    use Queueable, SerializesModels;

    public $association;

    /**
     * Create a new message instance.
     */
    public function __construct(User $association)
    {
        $this->association = $association;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🎉 Votre association a été vérifiée')
                    ->markdown('emails.association-verified');
    }
}