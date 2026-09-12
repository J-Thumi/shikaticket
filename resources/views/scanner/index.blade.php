@extends('layouts.app')

@section('content')

    <div class="max-w-md mx-auto px-4 py-8" x-data="scannerApp()">
        <h1 class="text-center text-xl font-black text-white mb-6">ShikaTicket Entry Scanner</h1>

        <!-- Camera Viewport / Feedback Canvas -->
        <div class="relative bg-black rounded-3xl border-2 border-slate-700 overflow-hidden aspect-square mb-6">
            <div id="video" class="w-full h-full object-cover"></div>
            
            <!-- Scan Status Overlay -->
            <div x-show="status !== 'idle'" x-cloak 
                 :class="{
                     'bg-emerald-600/90': status === 'granted',
                     'bg-rose-600/90': status === 'already_used' || status === 'invalid'
                 }"
                 class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center text-white backdrop-blur transition">
                <div class="text-4xl font-black mb-2" x-text="scanMessage"></div>
                <div class="text-lg font-bold" x-text="attendeeName"></div>
                <div class="text-sm opacity-80" x-text="ticketTier"></div>
                
                <button x-on:click="resetScanner()" class="mt-6 px-6 py-2 bg-white/20 hover:bg-white/30 rounded-xl font-bold border border-white/30">
                    Next Scan
                </button>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500">Point scanner at attendee ticket QR code to verify entry.</p>
    </div>

    <!-- Html5-QRCode Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function scannerApp() {
            return {
                status: 'idle',
                scanMessage: '',
                attendeeName: '',
                ticketTier: '',
                html5QrCode: null,
                init() {
                    this.html5QrCode = new Html5Qrcode("video");
                    this.startScanning();
                },
                startScanning() {
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length) {
                            this.html5QrCode.start(
                                { facingMode: "environment" },
                                { fps: 10, qrbox: { width: 250, height: 250 } },
                                (decodedText) => this.onScanSuccess(decodedText)
                            );
                        }
                    });
                },
                async onScanSuccess(decodedText) {
                    this.html5QrCode.pause();
                    try {
                        const payload = JSON.parse(decodedText);
                        const response = await fetch('{{ route("scanner.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ticket_code: payload.code,
                                qr_hash: payload.hash
                            })
                        });
                        const res = await response.json();
                        this.status = res.result;
                        this.scanMessage = res.message;
                        this.attendeeName = res.attendee || '';
                        this.ticketTier = res.tier || '';
                    } catch (e) {
                        this.status = 'invalid';
                        this.scanMessage = 'INVALID QR DATA';
                    }
                },
                resetScanner() {
                    this.status = 'idle';
                    this.html5QrCode.resume();
                }
            }
        }
    </script>
@endsection