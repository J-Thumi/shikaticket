@extends('layouts.app', ['title' => 'Contact Us — ShikaTicket'])

@section('content')
<div class="bg-paper min-h-screen py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="max-w-3xl mb-12">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-soft border border-line text-xs font-bold text-ink mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                Get in Touch
            </div>
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl md:text-5xl tracking-tight text-ink mb-4">
                We're here to help
            </h1>
            <p class="text-sm sm:text-base text-muted leading-relaxed">
                Have questions about an event, your tickets, hosting an event, or our referral programme? Reach out to our team and we'll get back to you promptly.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Left Column: Contact Form -->
            <div class="lg:col-span-7 bg-white border border-line rounded-3xl p-6 sm:p-10 shadow-sm">
                <h2 class="font-display font-bold text-xl text-ink mb-6">Send us a message</h2>

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs font-bold text-ink uppercase tracking-wider">Your Name</label>
                            <input type="text" id="name" name="name" required
                                class="w-full px-4 py-3 text-xs sm:text-sm bg-paper border border-line rounded-xl text-ink focus:outline-none focus:border-accent transition"
                                placeholder="Josphat Thumi">
                        </div>
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold text-ink uppercase tracking-wider">Email Address</label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-3 text-xs sm:text-sm bg-paper border border-line rounded-xl text-ink focus:outline-none focus:border-accent transition"
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label for="phone" class="block text-xs font-bold text-ink uppercase tracking-wider">Phone Number <span class="text-muted font-normal">(Optional)</span></label>
                            <input type="tel" id="phone" name="phone"
                                class="w-full px-4 py-3 text-xs sm:text-sm bg-paper border border-line rounded-xl text-ink focus:outline-none focus:border-accent transition"
                                placeholder="+254 700 000000">
                        </div>
                        <div class="space-y-1.5">
                            <label for="topic" class="block text-xs font-bold text-ink uppercase tracking-wider">Topic</label>
                            <select id="topic" name="topic" required
                                class="w-full px-4 py-3 text-xs sm:text-sm bg-paper border border-line rounded-xl text-ink focus:outline-none focus:border-accent transition">
                                <option value="" disabled selected>Select a topic</option>
                                <option value="ticketing">Ticketing & Orders</option>
                                <option value="organizer">Event Organizer Inquiry</option>
                                <option value="marketer">Marketer & Referral Program</option>
                                <option value="privacy">Privacy & Data Request</option>
                                <option value="other">General Support</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="message" class="block text-xs font-bold text-ink uppercase tracking-wider">Message</label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full px-4 py-3 text-xs sm:text-sm bg-paper border border-line rounded-xl text-ink focus:outline-none focus:border-accent transition"
                            placeholder="Describe how we can help you..."></textarea>
                    </div>

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 text-xs sm:text-sm font-bold text-white bg-accent hover:bg-accent-dark rounded-xl shadow-sm hover:-translate-y-0.5 transition duration-150">
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Right Column: Support Channels & Details -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Quick Info Card -->
                <div class="bg-white border border-line rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <h2 class="font-display font-bold text-lg text-ink">Direct Contact Information</h2>
                    
                    <div class="space-y-5 text-xs sm:text-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0 text-accent font-bold">
                                @
                            </div>
                            <div>
                                <p class="text-muted text-xs font-medium">Support Email</p>
                                <a href="mailto:[SUPPORT EMAIL]" class="font-bold text-ink hover:text-accent transition">[SUPPORT EMAIL]</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0 text-accent font-bold">
                                pr
                            </div>
                            <div>
                                <p class="text-muted text-xs font-medium">Privacy & Data Requests</p>
                                <a href="mailto:[PRIVACY CONTACT EMAIL]" class="font-bold text-ink hover:text-accent transition">[PRIVACY CONTACT EMAIL]</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0 text-accent font-bold">
                                web
                            </div>
                            <div>
                                <p class="text-muted text-xs font-medium">Platform Website</p>
                                <span class="font-bold text-ink">[SHIKATICKET WEBSITE]</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0 text-accent font-bold">
                                loc
                            </div>
                            <div>
                                <p class="text-muted text-xs font-medium">Business Address</p>
                                <span class="font-bold text-ink">[BUSINESS ADDRESS, IF APPLICABLE]</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legal Documentation Quick Links -->
                <div class="p-6 rounded-3xl bg-dark text-white space-y-4">
                    <h3 class="font-display font-bold text-sm tracking-wider uppercase text-accent">Looking for Legal Info?</h3>
                    <p class="text-xs text-[#c9c9cc] leading-relaxed">
                        Review our platform guidelines, customer responsibilities, and data protection practices.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="{{ route('legal.terms') }}" class="flex-1 text-center py-2.5 px-4 text-xs font-bold text-white bg-[#26262a] hover:bg-[#323237] rounded-xl transition">
                            Terms of Service
                        </a>
                        <a href="{{ route('legal.privacy') }}" class="flex-1 text-center py-2.5 px-4 text-xs font-bold text-white bg-[#26262a] hover:bg-[#323237] rounded-xl transition">
                            Privacy Policy
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
