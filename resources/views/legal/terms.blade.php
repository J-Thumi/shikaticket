@extends('layouts.app', ['title' => 'Terms of Service — ShikaTicket'])

@section('content')
<div x-data="{ activeSection: 'section-1' }" class="bg-paper min-h-screen">
    
    <!-- Header Banner -->
    <div class="border-b border-line bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-soft border border-line text-xs font-bold text-ink mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                    Legal Documentation
                </div>
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl md:text-5xl tracking-tight text-ink mb-4">
                    Terms of Service
                </h1>
                <p class="text-sm sm:text-base text-muted leading-relaxed mb-6">
                    Please read these terms carefully before using ShikaTicket to discover events, purchase tickets, or manage event listings.
                </p>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs font-medium text-muted pt-4 border-t border-line">
                    <div>
                        <span class="text-ink font-bold">Effective Date:</span> [INSERT DATE]
                    </div>
                    <span class="hidden sm:inline text-line">•</span>
                    <div>
                        <span class="text-ink font-bold">Last Updated:</span> [INSERT DATE]
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Layout: Sticky TOC + Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Left Sidebar: Table of Contents -->
            <aside class="hidden lg:block lg:col-span-4 sticky top-28">
                <div class="bg-white border border-line rounded-2xl p-6 shadow-sm">
                    <h2 class="font-display font-bold text-xs uppercase tracking-wider text-muted mb-4">
                        Table of Contents
                    </h2>
                    <nav class="space-y-1 max-h-[calc(100vh-14rem)] overflow-y-auto pr-2 text-xs">
                        @php
                            $sections = [
                                '1. Introduction',
                                '2. Definitions',
                                '3. Eligibility and Accounts',
                                '4. Customer Responsibilities',
                                '5. Orders and Payments',
                                '6. Tickets and Admission',
                                '7. Event Organizers',
                                '8. Marketers & Referrals',
                                '9. Cancellations & Refunds',
                                '10. Prohibited Activities',
                                '11. Intellectual Property',
                                '12. Third-Party Services',
                                '13. Platform Availability',
                                '14. Suspension & Termination',
                                '15. Limitation of Liability',
                                '16. Indemnity',
                                '17. Privacy',
                                '18. Changes to Terms',
                                '19. Governing Law & Disputes',
                                '20. Contact',
                            ];
                        @endphp

                        @foreach($sections as $index => $label)
                            @php $id = 'section-' . ($index + 1); @endphp
                            <a href="#{{ $id }}"
                               @click="activeSection = '{{ $id }}'"
                               :class="activeSection === '{{ $id }}' ? 'bg-soft text-ink font-bold border-l-2 border-accent' : 'text-muted hover:text-ink hover:bg-soft/50'"
                               class="block px-3 py-2 rounded-r-lg transition-all">
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-6 pt-5 border-t border-line">
                        <p class="text-xs text-muted mb-3">Related legal document:</p>
                        <a href="{{ route('legal.privacy') }}" class="inline-flex items-center justify-between w-full px-3.5 py-2.5 text-xs font-bold text-ink bg-soft hover:bg-line rounded-xl transition">
                            <span>Privacy Policy</span>
                            <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Right Column: Document Body -->
            <div class="lg:col-span-8 bg-white border border-line rounded-2xl p-6 sm:p-10 shadow-sm space-y-10 text-sm sm:text-[15px] text-[#3a3a40] leading-relaxed">

                <!-- 1. Introduction -->
                <section id="section-1" x-intersect.margin.-20%="activeSection = 'section-1'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">01</span>
                        Introduction
                    </h2>
                    <p>Welcome to ShikaTicket.</p>
                    <p>ShikaTicket is an online event ticketing platform that allows event organizers to publish and manage events, sell tickets, and manage attendees. Customers can discover events, purchase tickets and access their tickets electronically. Organizers may also work with marketers who promote events using referral links or codes.</p>
                    <p>These Terms of Service ("Terms") govern your access to and use of the ShikaTicket website, applications and related services.</p>
                    <p>ShikaTicket is operated by <strong class="text-ink">[OPERATOR LEGAL NAME]</strong>, referred to in these Terms as "ShikaTicket", "we", "our" or "us".</p>
                    <p class="p-4 rounded-xl bg-soft border border-line text-ink font-medium text-xs sm:text-sm">
                        By creating an account, accessing the platform or using any of its services, you agree to these Terms. If you do not agree, you should not use the platform.
                    </p>
                </section>

                <hr class="border-line">

                <!-- 2. Definitions -->
                <section id="section-2" x-intersect.margin.-20%="activeSection = 'section-2'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">02</span>
                        Definitions
                    </h2>
                    <p>For purposes of these Terms:</p>
                    <ul class="space-y-2.5 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span><strong class="text-ink">Platform:</strong> The ShikaTicket website and associated services.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span><strong class="text-ink">Customer:</strong> A person who uses ShikaTicket to discover or purchase event tickets.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span><strong class="text-ink">Organizer:</strong> A person or organization that creates, publishes or manages events through ShikaTicket.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span><strong class="text-ink">Marketer:</strong> A person authorized by an organizer to promote an event using a referral code or link.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span><strong class="text-ink">Event:</strong> An activity, gathering, performance, conference or other occasion published on ShikaTicket.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span><strong class="text-ink">Ticket:</strong> An electronic or other admission credential issued for an event.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span><strong class="text-ink">Order:</strong> A customer's request to purchase one or more tickets.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span><strong class="text-ink">Payment Provider:</strong> A third-party service that processes payments on behalf of ShikaTicket or an organizer.</span></li>
                    </ul>
                </section>

                <hr class="border-line">

                <!-- 3. Eligibility and Accounts -->
                <section id="section-3" x-intersect.margin.-20%="activeSection = 'section-3'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">03</span>
                        Eligibility and Accounts
                    </h2>
                    <p>You must provide accurate and complete information when creating an account.</p>
                    <p>You are responsible for:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Keeping your login credentials confidential.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Ensuring that information in your account remains accurate.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Notifying us if you suspect unauthorized access to your account.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>All activities carried out through your account, except where caused by our failure to exercise legally required care.</span></li>
                    </ul>
                    <p>You must not create an account using another person's identity or impersonate another person or organization.</p>
                    <p>If you are below the age at which you can independently enter into a binding agreement under applicable law, you must use the platform with the involvement and permission of a parent or legal guardian.</p>
                    <p>We may suspend or restrict accounts where we reasonably believe that they are being used fraudulently, unlawfully or in violation of these Terms.</p>
                </section>

                <hr class="border-line">

                <!-- 4. Customer Responsibilities -->
                <section id="section-4" x-intersect.margin.-20%="activeSection = 'section-4'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">04</span>
                        Customer Responsibilities
                    </h2>
                    <p>Customers may use ShikaTicket to browse events, purchase available tickets and access ticket information.</p>
                    <p>When purchasing a ticket, you agree to:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Provide accurate contact and payment information.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Review the event details before completing your purchase.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Pay the displayed ticket price and any disclosed applicable fees.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Avoid purchasing tickets for fraudulent, unlawful or unauthorized purposes.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Keep your ticket and QR code secure.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Follow the event organizer's reasonable admission and attendance requirements.</span></li>
                    </ul>
                    <p>A ticket does not guarantee admission where the ticket is invalid, duplicated, cancelled, refunded or otherwise subject to legitimate restrictions disclosed for the event.</p>
                </section>

                <hr class="border-line">

                <!-- 5. Orders and Payments -->
                <section id="section-5" x-intersect.margin.-20%="activeSection = 'section-5'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">05</span>
                        Orders and Payments
                    </h2>
                    <p>An order is subject to successful payment confirmation and ticket issuance.</p>
                    <p>Ticket prices and applicable charges will be displayed during checkout before you confirm your purchase.</p>
                    <p>Payments may be processed through third-party payment providers. Their services may be subject to additional terms and privacy policies.</p>
                    <p>ShikaTicket may receive payment status, transaction references and other information necessary to confirm and reconcile transactions.</p>
                    <p>An order should be considered successful only when the platform confirms the transaction and issues the corresponding ticket or tickets.</p>
                    <p>We are not responsible for delays caused by a payment provider, mobile network, bank or other third-party service, but we will take reasonable steps to investigate payment issues reported to us.</p>
                    <p>You must not attempt to manipulate payment confirmations, exploit payment callbacks or obtain tickets without completing the required payment.</p>
                </section>

                <hr class="border-line">

                <!-- 6. Tickets and Admission -->
                <section id="section-6" x-intersect.margin.-20%="activeSection = 'section-6'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">06</span>
                        Tickets and Admission
                    </h2>
                    <p>Tickets issued through ShikaTicket may contain unique identifiers or QR codes used to verify admission.</p>
                    <p>You agree not to:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Duplicate, forge or alter tickets.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Reproduce or distribute tickets for fraudulent purposes.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Use the same ticket for unauthorized multiple admissions.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Resell tickets where prohibited by the event's stated conditions or applicable law.</span></li>
                    </ul>
                    <p>The event organizer is responsible for establishing and communicating event-specific admission requirements.</p>
                    <p>ShikaTicket may provide ticket validation tools to organizers or their authorized representatives.</p>
                </section>

                <hr class="border-line">

                <!-- 7. Event Organizers -->
                <section id="section-7" x-intersect.margin.-20%="activeSection = 'section-7'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">07</span>
                        Event Organizers
                    </h2>
                    <p>Organizers are responsible for the events they publish and manage through ShikaTicket.</p>
                    <p>By publishing an event, an organizer confirms that:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>They are authorized to organize and promote the event.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Event descriptions, dates, venues, prices and other information are accurate.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>They have obtained permissions, licences and approvals required for the event.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>They will honour valid tickets issued for the event.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>They will communicate material event changes to customers.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>They will comply with applicable laws and regulations.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>They will handle customer information only for legitimate and disclosed purposes.</span></li>
                    </ul>
                    <p>Organizers are responsible for event planning, delivery, venue arrangements, safety, performers, event staff and other matters relating to the actual event.</p>
                    <p>ShikaTicket provides ticketing and event-management technology. Unless expressly agreed otherwise, ShikaTicket does not organize, host or control events published by independent organizers.</p>
                    <p>We may review, restrict, suspend or remove an event where we reasonably suspect fraud, unlawful activity, misleading information or a violation of these Terms.</p>
                </section>

                <hr class="border-line">

                <!-- 8. Marketers and Referral Programmes -->
                <section id="section-8" x-intersect.margin.-20%="activeSection = 'section-8'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">08</span>
                        Marketers and Referral Programmes
                    </h2>
                    <p>Where an organizer enables marketer participation, marketers may receive referral links or codes for promoting an event.</p>
                    <p>Marketers must:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Use only the referral codes or links assigned to them.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Promote events honestly and accurately.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Avoid misleading advertising or false promises.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Not impersonate ShikaTicket or an event organizer.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Not use spam, deceptive practices or unauthorized access to promote events.</span></li>
                    </ul>
                    <p>Any commission arrangement, including percentage-based or fixed commissions, is determined by the applicable organizer and the platform's available settings.</p>
                    <p>A commission is calculated according to the applicable arrangement and eligible transactions recorded by ShikaTicket.</p>
                    <p>ShikaTicket may withhold, reverse or investigate commissions associated with fraudulent, refunded, cancelled or disputed transactions, subject to the applicable agreement and law.</p>
                </section>

                <hr class="border-line">

                <!-- 9. Cancellations, Postponements and Refunds -->
                <section id="section-9" x-intersect.margin.-20%="activeSection = 'section-9'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">09</span>
                        Cancellations, Postponements and Refunds
                    </h2>
                    <p>The event organizer is responsible for communicating event cancellations, postponements and material changes.</p>
                    <p>Refund eligibility, applicable deadlines, procedures and any permitted deductions must be communicated through the relevant event's refund policy and applicable checkout disclosures.</p>
                    <p>Where an event is cancelled, postponed or materially changed, customers should follow the refund or resolution process communicated for that event.</p>
                    <p>Refunds may be subject to verification and processing by the relevant payment provider. Processing times may therefore vary.</p>
                    <p>ShikaTicket may assist customers and organizers in resolving ticketing and payment disputes. Such assistance does not automatically make ShikaTicket the organizer of the event or the party responsible for delivering it.</p>
                    <p>Nothing in these Terms excludes any consumer rights or remedies that cannot lawfully be excluded.</p>
                </section>

                <hr class="border-line">

                <!-- 10. Prohibited Activities -->
                <section id="section-10" x-intersect.margin.-20%="activeSection = 'section-10'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">10</span>
                        Prohibited Activities
                    </h2>
                    <p>You must not use ShikaTicket to:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Commit fraud or facilitate unlawful activity.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Publish false, misleading or unauthorized events.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Sell counterfeit or invalid tickets.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Manipulate ticket sales, payments, referral records or commissions.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Access accounts or data without authorization.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Interfere with platform security or availability.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Upload malicious code or attempt to compromise the platform.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Harass, threaten or deceive other users.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Violate intellectual property, privacy or other legal rights.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Use the platform in a manner prohibited by applicable law.</span></li>
                    </ul>
                    <p>We may investigate suspected violations and take appropriate action, including restricting access, suspending accounts, removing events or referring suspected unlawful conduct to relevant authorities.</p>
                </section>

                <hr class="border-line">

                <!-- 11. Intellectual Property -->
                <section id="section-11" x-intersect.margin.-20%="activeSection = 'section-11'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">11</span>
                        Intellectual Property
                    </h2>
                    <p>ShikaTicket and its platform components, including its software, branding, design and original content, are owned by or licensed to <strong class="text-ink">[OPERATOR LEGAL NAME]</strong>.</p>
                    <p>You may not copy, modify, distribute, reverse engineer or commercially exploit platform components without authorization, except where permitted by law.</p>
                    <p>Organizers retain responsibility for ensuring that they have the necessary rights to upload and publish event names, descriptions, photographs, logos, videos and other materials.</p>
                    <p>By uploading content, organizers grant ShikaTicket a limited, non-exclusive permission to host, display and use that content as necessary to operate, promote and provide the relevant platform services.</p>
                </section>

                <hr class="border-line">

                <!-- 12. Third-Party Services -->
                <section id="section-12" x-intersect.margin.-20%="activeSection = 'section-12'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">12</span>
                        Third-Party Services
                    </h2>
                    <p>ShikaTicket may integrate with third-party services, including payment providers, email services, SMS providers, authentication providers and hosting providers.</p>
                    <p>Those services may have their own terms, privacy notices and operational requirements.</p>
                    <p>We are not responsible for independent third-party services, but we will take reasonable steps to work with relevant providers where their services affect the operation of ShikaTicket.</p>
                </section>

                <hr class="border-line">

                <!-- 13. Platform Availability -->
                <section id="section-13" x-intersect.margin.-20%="activeSection = 'section-13'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">13</span>
                        Platform Availability
                    </h2>
                    <p>We aim to keep ShikaTicket available and functioning reliably.</p>
                    <p>However, uninterrupted availability cannot be guaranteed. The platform may occasionally be unavailable due to maintenance, updates, technical issues, security incidents or circumstances beyond our reasonable control.</p>
                    <p>We may modify, suspend or discontinue features where reasonably necessary.</p>
                </section>

                <hr class="border-line">

                <!-- 14. Suspension and Termination -->
                <section id="section-14" x-intersect.margin.-20%="activeSection = 'section-14'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">14</span>
                        Suspension and Termination
                    </h2>
                    <p>You may stop using ShikaTicket at any time.</p>
                    <p>We may suspend or terminate access where:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>You materially breach these Terms.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Your account is associated with fraud or unlawful activity.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Your conduct threatens platform security or other users.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>We are legally required to do so.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Continued access creates a material operational or security risk.</span></li>
                    </ul>
                    <p>Suspension or termination does not remove obligations or liabilities that arose before termination, including valid payment, refund or dispute obligations.</p>
                </section>

                <hr class="border-line">

                <!-- 15. Limitation of Liability -->
                <section id="section-15" x-intersect.margin.-20%="activeSection = 'section-15'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">15</span>
                        Limitation of Liability
                    </h2>
                    <p>To the extent permitted by applicable law, ShikaTicket is not responsible for:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>The actual organization, quality, safety or delivery of independently organized events.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Inaccurate event information supplied by an organizer.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Losses caused by a user's failure to protect their account or ticket.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Interruptions caused by third-party services or circumstances beyond our reasonable control.</span></li>
                    </ul>
                    <p>Nothing in these Terms excludes liability where such exclusion is prohibited by law, including liability that cannot lawfully be limited.</p>
                </section>

                <hr class="border-line">

                <!-- 16. Indemnity -->
                <section id="section-16" x-intersect.margin.-20%="activeSection = 'section-16'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">16</span>
                        Indemnity
                    </h2>
                    <p>To the extent permitted by applicable law, organizers and other users agree to be responsible for claims, losses or expenses arising from their unlawful conduct, material breach of these Terms or infringement of another person's rights.</p>
                    <p>This provision does not remove any rights or responsibilities that applicable law does not permit us to exclude.</p>
                </section>

                <hr class="border-line">

                <!-- 17. Privacy -->
                <section id="section-17" x-intersect.margin.-20%="activeSection = 'section-17'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">17</span>
                        Privacy
                    </h2>
                    <p>Your use of ShikaTicket is also governed by our <a href="{{ route('legal.privacy') }}" class="text-accent hover:text-accent-dark font-bold underline underline-offset-4">Privacy Policy</a>, which explains how we collect, use, store and share personal information.</p>
                    <p>By using the platform, you acknowledge that you have been given access to the Privacy Policy.</p>
                </section>

                <hr class="border-line">

                <!-- 18. Changes to These Terms -->
                <section id="section-18" x-intersect.margin.-20%="activeSection = 'section-18'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">18</span>
                        Changes to These Terms
                    </h2>
                    <p>We may update these Terms from time to time.</p>
                    <p>Where changes are material, we will take reasonable steps to notify affected users through the platform or other appropriate communication channels.</p>
                    <p>The updated Terms will state their effective date. Continued use of ShikaTicket after the effective date constitutes acceptance of the updated Terms where permitted by law.</p>
                </section>

                <hr class="border-line">

                <!-- 19. Governing Law and Disputes -->
                <section id="section-19" x-intersect.margin.-20%="activeSection = 'section-19'" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">19</span>
                        Governing Law and Disputes
                    </h2>
                    <p>These Terms are governed by the laws of Kenya.</p>
                    <p>We encourage users to contact us first so that disputes can be addressed through reasonable discussion.</p>
                    <p>Where a dispute cannot be resolved informally, it may be referred to the competent courts of Kenya, subject to applicable law.</p>
                </section>

                <hr class="border-line">

                <!-- 20. Contact -->
                <section id="section-20" x-intersect.margin.-20%="activeSection = 'section-20'" class="scroll-mt-28 space-y-4">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">20</span>
                        Contact
                    </h2>
                    <p>For questions about these Terms, contact:</p>
                    <div class="p-6 rounded-2xl bg-soft border border-line grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                        <div>
                            <p class="text-muted text-xs font-medium">Operator</p>
                            <p class="font-bold text-ink mt-0.5">[OPERATOR LEGAL NAME]</p>
                        </div>
                        <div>
                            <p class="text-muted text-xs font-medium">Platform</p>
                            <p class="font-bold text-ink mt-0.5">ShikaTicket</p>
                        </div>
                        <div>
                            <p class="text-muted text-xs font-medium">Website</p>
                            <p class="font-bold text-ink mt-0.5">[SHIKATICKET WEBSITE]</p>
                        </div>
                        <div>
                            <p class="text-muted text-xs font-medium">Email</p>
                            <p class="font-bold text-accent mt-0.5">[SUPPORT EMAIL]</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-muted text-xs font-medium">Address</p>
                            <p class="font-bold text-ink mt-0.5">[BUSINESS ADDRESS, IF APPLICABLE]</p>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</div>
@endsection