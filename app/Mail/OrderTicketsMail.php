<?php
namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderTicketsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public int $orderId
    ) {}

    public function build()
    {
        $order = Order::with([
            'tickets.ticketType',
            'event',
        ])->findOrFail($this->orderId);

        $tickets = $order->tickets->map(function ($ticket) {
            $qrPayload = json_encode([
                'code' => $ticket->ticket_code,
                'hash' => $ticket->qr_code_hash,
            ]);

            $qrCodeSvg = base64_encode(
                QrCode::format('svg')
                    ->size(240)
                    ->errorCorrection('H')
                    ->generate($qrPayload)
            );

            return [
                'ticket' => $ticket,
                'qrCodeSvg' => $qrCodeSvg,
            ];
        });

        $supportEmail = config(
            'services.shikaticket.support_email',
            config('mail.from.address')
        );

        $supportPhone = config(
            'services.shikaticket.support_phone'
        );

        $website = config('app.url');

        $pdf = Pdf::loadView('tickets.mailpdf', [
            'order' => $order,
            'event' => $order->event,
            'tickets' => $tickets,
            'ticketCount' => $tickets->count(),
            'supportEmail' => $supportEmail,
            'supportPhone' => $supportPhone,
            'website' => $website,
        ])->setPaper('a4', 'portrait');

        return $this
            ->from(config('mail.from.address'), 'ShikaTicket')
            ->subject(
                'Your Event Tickets Are Ready - ' . $order->order_number
            )
            ->view('emails.orders.tickets', [
                'order' => $order,
            ])
            ->attachData(
                $pdf->output(),
                'ShikaTicket-' . $order->order_number . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}
