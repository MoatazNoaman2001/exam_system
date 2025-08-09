<?php 



namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;
    public string $replyMessage;

    public function __construct(Contact $contact, string $replyMessage)
    {
        $this->contact = $contact;
        $this->replyMessage = $replyMessage;
    }

    public function build()
    {
        return $this->subject('Re: ' . $this->contact->subject . ' - Sprint Skills Support')
                    ->view('emails.contact-reply')
                    ->with([
                        'contact' => $this->contact,
                        'replyMessage' => $this->replyMessage
                    ]);
    }
}