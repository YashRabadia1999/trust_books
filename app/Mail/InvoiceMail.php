<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $student;
    public $items;        // fee items
    public $totalAmount;  // total invoice amount
    public $dueDate;      // invoice due date

    /**
     * Create a new message instance.
     */
  public function __construct($invoice, $student)
{
    $this->invoice = $invoice;
    $this->student = $student;

    $rawItems = !empty($invoice->items) ? json_decode($invoice->items, true) : [];
    $this->items = [];

    $total = 0;
    foreach ($rawItems as $item) {
        $service = \Workdo\ProductService\Entities\ProductService::find($item['product_id']);
        $price = $service->sale_price ?? 0; // ✅ Use sale_price
        $quantity = $item['quantity'] ?? 1;
        $this->items[] = [
            'service' => $service->name ?? 'Unknown Service',
            'description' => $item['description'] ?? '-',
            'quantity' => $quantity,
            'price' => $price,
            'total' => $price * $quantity,
        ];
        $total += $price * $quantity;
    }

    $this->totalAmount = $total;
    $this->dueDate = $invoice->due_date ?? now()->addDays(7)->format('Y-m-d');

    // Log::info('Invoice data for mailing:', [
    //     'invoice' => $invoice->toArray(),
    //     'student' => $student->toArray(),
    //     'items' => $this->items,
    //     'totalAmount' => $this->totalAmount,
    // ]);
}


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Mail'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.invoice', // Blade view
            with: [
                'invoice' => $this->invoice,
                'student' => $this->student,
                'items' => $this->items,
                'totalAmount' => $this->totalAmount,
                'dueDate' => $this->dueDate
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
