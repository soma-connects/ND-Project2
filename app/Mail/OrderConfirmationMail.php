<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $confirmationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $confirmationUrl)
    {
        $this->order = $order->loadMissing('items.product');
        $this->confirmationUrl = $confirmationUrl;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Your Order Confirmation')
            ->view('emails.orders.confirmation')
            ->with([
                'order' => $this->order,
                'confirmationUrl' => $this->confirmationUrl,
            ]);
    }
}
