<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\Order;

class EventCancelledRefund extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, Order $order)
    {
        $this->event = $event;
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address('noreply@easytix.id', 'EasyTix'),
            subject: 'Pemberitahuan Refund: Event ' . $this->event->name . ' Dibatalkan',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-cancelled-refund',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
