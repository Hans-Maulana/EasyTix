<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class TicketPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $orderItems;
    public $userName;

    /**
     * Create a new message instance.
     * 
     * @param object $order     Order data (id, total_amount, payment_method, created_at)
     * @param array  $orderItems Array of ticket items with qr_code paths
     * @param string $userName  User's name
     */
    public function __construct($order, array $orderItems, string $userName)
    {
        $this->order = $order;
        $this->orderItems = $orderItems;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Anda - Pesanan #' . $this->order->id . ' | EasyTix',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ticket-purchased',
            with: [
                'order'      => $this->order,
                'orderItems' => $this->orderItems,
                'userName'   => $this->userName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->orderItems as $item) {
            if (!empty($item['qr_code'])) {
                $qrPath = storage_path('app/' . $item['qr_code']);
                if (file_exists($qrPath)) {
                    $attachments[] = Attachment::fromPath($qrPath)
                        ->as('QR_Tiket_' . ($item['owner_name'] ?? 'Guest') . '_' . basename($item['qr_code']))
                        ->withMime('image/svg+xml');
                }
            }
        }

        return $attachments;
    }
}
