@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-10">
    <!-- Header Controls -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-muted hover:text-ink transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Wallet</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('tickets.pdf', $ticket) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-ink bg-white border border-line rounded-xl hover:bg-soft transition shadow-sm">
                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Download PDF</span>
            </a>

            <button id="downloadBtn" onclick="downloadTicketCard()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-white bg-accent hover:bg-accent-dark rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Save Image</span>
            </button>
        </div>
    </div>

    <!-- Stylized Ticket Card Target -->
    <div id="ticketCard" class="bg-white border border-line rounded-[32px] overflow-hidden shadow-xl">
        <!-- Event Header Banner -->
        <div class="bg-gradient-to-br from-dark to-slate-900 text-white p-7 relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-accent/10 rounded-full blur-2xl"></div>
            
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-accent bg-accent/10 px-3 py-1 rounded-full border border-accent/20">
                    {{ $ticket->ticketType->name ?? 'Standard Pass' }}
                </span>
                <span class="text-[11px] font-bold text-[#929298]">
                    #{{ $ticket->ticket_code }}
                </span>
            </div>

            <h1 class="font-display text-2xl font-black tracking-tight text-white mb-2 leading-tight">
                {{ $ticket->order->event->title ?? 'Event Entry Pass' }}
            </h1>
            
            <p class="text-xs text-[#929298] font-medium flex items-center gap-2">
                <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ $ticket->order->event->venue_name ?? 'Location details on ticket' }}</span>
            </p>
        </div>

        <!-- Ticket Separation Line with Notch Effects -->
        <div class="relative bg-paper py-3 border-y border-dashed border-line flex items-center justify-between px-6">
            <div class="w-6 h-6 bg-paper border-r border-line rounded-full -ml-9 shadow-inner"></div>
            <div class="text-[10px] font-extrabold uppercase tracking-widest text-muted">Gate Verification Pass</div>
            <div class="w-6 h-6 bg-paper border-l border-line rounded-full -mr-9 shadow-inner"></div>
        </div>

        <!-- QR Code Container -->
        <div class="p-8 text-center bg-paper">
            <div class="bg-white p-5 rounded-2xl border border-line inline-block shadow-md mb-3">
                <div id="qrcode" style="width:170px;height:170px;" class="flex justify-center items-center"></div>
            </div>
            <p class="text-[11px] font-bold text-muted uppercase tracking-wider">Scan for Entry Authorization</p>
        </div>

        <!-- Attendee & Order Metadata -->
        <div class="p-6 bg-white space-y-4 text-xs border-t border-line">
            <div class="grid grid-cols-2 gap-4 pb-4 border-b border-line/60">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Attendee Name</span>
                    <span class="font-extrabold text-ink text-sm">{{ $ticket->order->customer_name }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Pass Status</span>
                    <span class="inline-flex items-center gap-1.5 font-extrabold {{ $ticket->status === 'used' ? 'text-rose-600' : 'text-emerald-600' }}">
                        <span class="w-2 h-2 rounded-full {{ $ticket->status === 'used' ? 'bg-rose-600' : 'bg-emerald-600' }}"></span>
                        {{ strtoupper($ticket->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pb-4 border-b border-line/60">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Order Ref</span>
                    <span class="font-mono font-bold text-ink">{{ $ticket->order->order_number }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Contact</span>
                    <span class="font-bold text-ink truncate block">{{ $ticket->order->customer_email }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pb-4 border-b border-line/60">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Phone Number</span>
                    <span class="font-bold text-ink">{{ $ticket->order->customer_phone ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Ticket Type</span>
                    <span class="font-bold text-ink">{{ $ticket->ticketType->name ?? 'Standard Pass' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @if(!empty($ticket->seat_number) || !empty($ticket->ticketType->price))
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Seat / Section</span>
                        <span class="font-bold text-ink">{{ $ticket->seat_number ?? 'General Admission' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Ticket Price</span>
                        <span class="font-bold text-ink">{{ isset($ticket->ticketType->price) ? number_format($ticket->ticketType->price, 2) : 'N/A' }}</span>
                    </div>
                @else
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Purchase Date</span>
                        <span class="font-bold text-ink">{{ optional($ticket->order->created_at)->format('d M Y') ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-muted block mb-0.5">Gate / Entrance</span>
                        <span class="font-bold text-ink">{{ $ticket->gate ?? 'Main Entrance' }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.4.1/dist/easy.qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.min.js"></script>

<script>
    let qrGenerated = false;

    function generateTicketQRCode() {
        return new Promise((resolve, reject) => {
            const container = document.getElementById('qrcode');

            if (!container) {
                reject(new Error('QR container not found.'));
                return;
            }

            container.innerHTML = '';

            try {
                new QRCode(container, {
                    text: JSON.stringify({!! $qrPayload !!}),
                    width: 170,
                    height: 170,
                    colorDark: '#0f172a',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            } catch (error) {
                reject(error);
                return;
            }

            const startedAt = Date.now();

            const waitForQR = () => {
                const canvas = container.querySelector('canvas');
                const img = container.querySelector('img');

                if (canvas && canvas.width > 0 && canvas.height > 0) {
                    qrGenerated = true;
                    resolve();
                    return;
                }

                if (img && img.complete && img.naturalWidth > 0) {
                    qrGenerated = true;
                    resolve();
                    return;
                }

                if (Date.now() - startedAt > 5000) {
                    reject(new Error('QR generation timed out.'));
                    return;
                }

                requestAnimationFrame(waitForQR);
            };

            waitForQR();
        });
    }

    async function downloadTicketCard() {
        const btn = document.getElementById('downloadBtn');
        const ticketCard = document.getElementById('ticketCard');

        if (!ticketCard) {
            console.error('Ticket card not found.');
            return;
        }

        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span>Preparing...</span>';

        try {
            if (!qrGenerated) {
                await generateTicketQRCode();
            }

            if (document.fonts && document.fonts.ready) {
                await document.fonts.ready;
            }

            // Give the browser one paint cycle before capture.
            await new Promise(resolve => {
                requestAnimationFrame(() => requestAnimationFrame(resolve));
            });

            btn.innerHTML = '<span>Saving...</span>';

            // htmlToImage exposes toPng / toCanvas / toBlob etc.
            const dataUrl = await htmlToImage.toPng(ticketCard, {
                pixelRatio: 2,
                backgroundColor: '#ffffff',
                cacheBust: true,

                // Skip the little circular notch decorations if they
                // ever cause edge artifacts - remove this filter if
                // you don't need it.
                skipFonts: false,
            });

            const link = document.createElement('a');
            link.download = 'Pass-{{ $ticket->ticket_code }}.png';
            link.href = dataUrl;
            document.body.appendChild(link);
            link.click();
            link.remove();

        } catch (error) {
            console.error('Ticket image generation failed:', error);
            alert('Unable to generate the ticket image. Please try again.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            await generateTicketQRCode();
            console.log('Ticket QR code ready.');
        } catch (error) {
            console.error('QR generation failed:', error);
        }
    });
</script>
@endsection