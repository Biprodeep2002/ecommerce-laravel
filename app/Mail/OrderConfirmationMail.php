<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $username;
    public $orderId;
    public $cart;
    public $total;

    public function __construct($username, $orderId, $cart, $total)
    {
        //
        $this->username = $username;
        $this->orderId = $orderId;
        $this->cart = $cart;
        $this->total = $total;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('emails.order_confirmation')
                    ->with([
                        'username' => $this->username,
                        'orderId' => $this->orderId,
                        'cart' => $this->cart,
                        'total' => $this->total,
                    ]);
    }
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Order Confirmation Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
