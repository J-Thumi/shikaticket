@extends('layouts.app', ['title' => 'Privacy Policy — ShikaTicket'])

@section('content')
<div x-data="{ activeSection: 'privacy-1' }" class="bg-paper min-h-screen">
    
    <!-- Header Banner -->
    <div class="border-b border-line bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-soft border border-line text-xs font-bold text-ink mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                    Data Protection & Privacy
                </div>
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl md:text-5xl tracking-tight text-ink mb-4">
                    Privacy Policy
                </h1>
                <p class="text-sm sm:text-base text-muted leading-relaxed mb-6">
                    How ShikaTicket collects, uses, stores, and protects your personal information in accordance with the Kenya Data Protection Act, 2019.
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
                                '2. Data Controller Responsibility',
                                '3. Personal Information We Collect',
                                '4. How We Use Your Information',
                                '5. How We Share Information',
                                '6. Cookies & Technologies',
                                '7. Data Storage & Security',
                                '8. Data Retention',
                                '9. International Data Transfers',
                                '10. Your Data Protection Rights',
                                '11. Third-Party Websites',
                                '12. Children\'s Privacy',
                                '13. Changes to Policy',
                                '14. Contact & Privacy Requests',
                            ];
                        @endphp

                        @foreach($sections as $index => $label)
                            @php $id = 'privacy-' . ($index + 1); @endphp
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
                        <a href="{{ route('legal.terms') }}" class="inline-flex items-center justify-between w-full px-3.5 py-2.5 text-xs font-bold text-ink bg-soft hover:bg-line rounded-xl transition">
                            <span>Terms of Service</span>
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
                <section id="privacy-1" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">01</span>
                        Introduction
                    </h2>
                    <p>ShikaTicket respects your privacy and is committed to handling personal information responsibly.</p>
                    <p>This Privacy Policy explains how ShikaTicket collects, uses, stores, shares and protects personal information when you visit our website, create an account, purchase tickets, organize events, participate in referral programmes or otherwise use our services.</p>
                    <p>ShikaTicket is operated by <strong class="text-ink">[OPERATOR LEGAL NAME]</strong>, referred to in this policy as "ShikaTicket", "we", "our" or "us".</p>
                    <p class="p-4 rounded-xl bg-soft border border-line text-ink font-medium text-xs sm:text-sm">
                        This policy is intended to explain our personal data practices in accordance with applicable Kenyan data protection laws, including the Data Protection Act, 2019 and relevant regulations.
                    </p>
                </section>

                <hr class="border-line">

                <!-- 2. Who Is Responsible for Your Personal Data? -->
                <section id="privacy-2" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">02</span>
                        Who Is Responsible for Your Personal Data?
                    </h2>
                    <p>ShikaTicket determines how certain personal information is processed to operate the platform. For those activities, ShikaTicket acts as a data controller.</p>
                    <p>Examples include:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Creating and managing user accounts.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Operating the ticketing platform.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Processing and reconciling orders.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Preventing fraud and protecting platform security.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Handling customer support requests.</span></li>
                    </ul>
                    <p>Event organizers may separately determine how attendee information is used for their own event-management activities. In those circumstances, an organizer may act as an independent data controller.</p>
                    <p>Where ShikaTicket processes personal information on behalf of an organizer, the parties' respective responsibilities will depend on the actual processing arrangement.</p>
                </section>

                <hr class="border-line">

                <!-- 3. Personal Information We Collect -->
                <section id="privacy-3" class="scroll-mt-28 space-y-6">
                    <div class="space-y-2">
                        <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                            <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">03</span>
                            Personal Information We Collect
                        </h2>
                        <p>The information we collect depends on how you use ShikaTicket.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- 3.1 -->
                        <div class="p-5 rounded-2xl bg-paper border border-line space-y-2">
                            <h3 class="font-display font-bold text-sm text-ink">3.1 Account Information</h3>
                            <p class="text-xs text-muted">When you register, we may collect:</p>
                            <ul class="space-y-1.5 text-xs">
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Full name.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Email address.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Phone number.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Password credentials, stored in protected form.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Account role, such as customer, organizer or marketer.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Account identifiers and registration information.</span></li>
                            </ul>
                        </div>

                        <!-- 3.2 -->
                        <div class="p-5 rounded-2xl bg-paper border border-line space-y-2">
                            <h3 class="font-display font-bold text-sm text-ink">3.2 Google Authentication Information</h3>
                            <p class="text-xs text-muted">If you sign in using Google, we may receive:</p>
                            <ul class="space-y-1.5 text-xs">
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Google account identifier.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Name and Email address.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Email verification status.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Profile image, where provided.</span></li>
                            </ul>
                            <p class="text-xs text-muted pt-1">We use this information to authenticate you, create or link your account and provide requested services. We do not receive your Google account password.</p>
                        </div>

                        <!-- 3.3 -->
                        <div class="p-5 rounded-2xl bg-paper border border-line space-y-2">
                            <h3 class="font-display font-bold text-sm text-ink">3.3 Ticket and Order Information</h3>
                            <p class="text-xs text-muted">When you purchase tickets, we may collect:</p>
                            <ul class="space-y-1.5 text-xs">
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Event and ticket type & quantity purchased.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Order identifiers, ticket identifiers, and QR codes.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Purchase date, time, and order status.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Relevant attendee information.</span></li>
                            </ul>
                        </div>

                        <!-- 3.4 -->
                        <div class="p-5 rounded-2xl bg-paper border border-line space-y-2">
                            <h3 class="font-display font-bold text-sm text-ink">3.4 Payment Information</h3>
                            <p class="text-xs text-muted">Information to initiate, verify and reconcile payments:</p>
                            <ul class="space-y-1.5 text-xs">
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Transaction references, payment status, and amount paid.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Payment method info supplied by the provider.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Relevant transaction timestamps.</span></li>
                            </ul>
                            <p class="text-xs text-muted pt-1">Sensitive payment credentials processed directly by third-party providers are subject to their security practices.</p>
                        </div>

                        <!-- 3.5 -->
                        <div class="p-5 rounded-2xl bg-paper border border-line space-y-2">
                            <h3 class="font-display font-bold text-sm text-ink">3.5 Organizer Information</h3>
                            <p class="text-xs text-muted">If you register or operate as an event organizer:</p>
                            <ul class="space-y-1.5 text-xs">
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Organizer or business name and contact details.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Event descriptions, venue info, and ticket pricing.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Payout or payment-related information where required.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Records relating to event sales and management.</span></li>
                            </ul>
                        </div>

                        <!-- 3.6 -->
                        <div class="p-5 rounded-2xl bg-paper border border-line space-y-2">
                            <h3 class="font-display font-bold text-sm text-ink">3.6 Marketer Information</h3>
                            <p class="text-xs text-muted">If you participate as a marketer:</p>
                            <ul class="space-y-1.5 text-xs">
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Name, contact information, and referral codes.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Assigned events, referral records, and sales records.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Commission configuration and calculation records.</span></li>
                                <li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-1.5 shrink-0"></span><span>Payment or payout details where applicable.</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- 3.7 -->
                    <div class="p-5 rounded-2xl bg-paper border border-line space-y-2">
                        <h3 class="font-display font-bold text-sm text-ink">3.7 Technical Information</h3>
                        <p class="text-xs text-muted">When you access the platform, we may automatically collect certain technical information, such as:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-xs pt-1">
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span><span>IP address</span></div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span><span>Browser and device information</span></div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span><span>Operating system</span></div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span><span>Access timestamps</span></div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span><span>Pages and features accessed</span></div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span><span>Error and security logs</span></div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span><span>Session and cookie identifiers</span></div>
                        </div>
                    </div>
                </section>

                <hr class="border-line">

                <!-- 4. How We Use Your Information -->
                <section id="privacy-4" class="scroll-mt-28 space-y-4">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">04</span>
                        How We Use Your Information
                    </h2>
                    <p>We use personal information for legitimate and disclosed purposes, including:</p>
                    <div class="space-y-3">
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Account Management</h3>
                            <p class="text-xs text-muted mt-0.5">To create accounts, authenticate users, maintain profiles and provide account-related services.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Ticketing and Orders</h3>
                            <p class="text-xs text-muted mt-0.5">To process ticket orders, issue tickets, verify admission and provide customers with purchase information.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Payment Verification</h3>
                            <p class="text-xs text-muted mt-0.5">To initiate and verify payments, reconcile transactions, investigate failed payments and prevent fraudulent activity.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Event Management</h3>
                            <p class="text-xs text-muted mt-0.5">To allow organizers to create events, manage ticket sales, communicate event information and administer attendees.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Referral and Commission Management</h3>
                            <p class="text-xs text-muted mt-0.5">To track marketer referrals, attribute eligible sales and calculate commissions.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Customer Support</h3>
                            <p class="text-xs text-muted mt-0.5">To respond to questions, investigate complaints and resolve account, ticketing and payment issues.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Security and Fraud Prevention</h3>
                            <p class="text-xs text-muted mt-0.5">To protect accounts, identify suspicious transactions, enforce platform rules and maintain system security.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Legal and Regulatory Requirements</h3>
                            <p class="text-xs text-muted mt-0.5">To comply with applicable laws, lawful requests, accounting requirements and legitimate legal processes.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Platform Improvement</h3>
                            <p class="text-xs text-muted mt-0.5">To understand platform performance, troubleshoot technical issues and improve our services.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-soft/60 border border-line">
                            <h3 class="font-bold text-ink text-sm">Communications</h3>
                            <p class="text-xs text-muted mt-0.5">To send transactional messages such as registration confirmations, OTPs, order updates, payment notifications and ticket information. We may send promotional communications where permitted by law and where the required consent or other lawful basis has been obtained. You may opt out of promotional communications without affecting essential service messages.</p>
                        </div>
                    </div>
                </section>

                <hr class="border-line">

                <!-- 5. How We Share Personal Information -->
                <section id="privacy-5" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">05</span>
                        How We Share Personal Information
                    </h2>
                    <p class="font-bold text-ink">We do not sell your personal information.</p>
                    <p>We may share relevant information with the following categories of recipients where necessary:</p>
                    <ul class="space-y-3 pl-4">
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span>
                            <span><strong class="text-ink">Event Organizers:</strong> When you purchase a ticket, relevant order and attendee information may be made available to the organizer to manage the event, verify admission and communicate necessary event updates. Organizers should use this information only for legitimate event-related purposes and in accordance with applicable data protection requirements.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span>
                            <span><strong class="text-ink">Payment Providers:</strong> We share relevant transaction information with payment providers to process, verify and reconcile payments.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span>
                            <span><strong class="text-ink">Communication Providers:</strong> We may use email and SMS providers to deliver OTPs, confirmations, receipts, ticket information and other service communications.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span>
                            <span><strong class="text-ink">Hosting and Technical Providers:</strong> We may use hosting, infrastructure, database, security, monitoring and other technology providers to operate the platform.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span>
                            <span><strong class="text-ink">Authorized Marketers:</strong> Marketers may receive referral and sales information necessary to understand their attributed sales and commissions. Access to customer personal information should be limited to what is genuinely necessary and authorized.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span>
                            <span><strong class="text-ink">Legal and Regulatory Authorities:</strong> We may disclose information where required by applicable law, court orders or lawful regulatory requests, or where necessary to protect our legal rights and the safety of users.</span>
                        </li>
                    </ul>
                    <p>Where we use third-party providers to process personal information, we take reasonable steps to ensure that appropriate safeguards are in place.</p>
                </section>

                <hr class="border-line">

                <!-- 6. Cookies and Similar Technologies -->
                <section id="privacy-6" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">06</span>
                        Cookies and Similar Technologies
                    </h2>
                    <p>ShikaTicket may use cookies and similar technologies to:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Maintain user sessions.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Support authentication.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Remember relevant preferences.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Protect the platform against security threats.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Understand website performance and usage.</span></li>
                    </ul>
                    <p>Some cookies may be essential for the platform to function.</p>
                    <p>You can manage cookies through your browser settings. Disabling certain cookies may affect the availability or functionality of some platform features.</p>
                </section>

                <hr class="border-line">

                <!-- 7. Data Storage and Security -->
                <section id="privacy-7" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">07</span>
                        Data Storage and Security
                    </h2>
                    <p>We take reasonable technical and organizational measures to protect personal information against unauthorized access, alteration, disclosure, loss or destruction.</p>
                    <p>These measures may include:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Password hashing.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Access controls and authorization checks.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Secure communication using HTTPS.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Database and infrastructure security measures.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Monitoring and security logging.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Restricted access to sensitive information.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Regular maintenance and security updates.</span></li>
                    </ul>
                    <p>No internet-based service can guarantee absolute security. We therefore cannot promise that information will be completely immune from every possible security incident.</p>
                </section>

                <hr class="border-line">

                <!-- 8. How Long We Retain Information -->
                <section id="privacy-8" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">08</span>
                        How Long We Retain Information
                    </h2>
                    <p>We retain personal information only for as long as reasonably necessary for the purposes for which it was collected, subject to applicable legal obligations.</p>
                    <p>Retention periods may depend on:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Whether your account remains active.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Whether you have outstanding orders or disputes.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Payment reconciliation and accounting requirements.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Event and ticket verification requirements.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Fraud prevention and security needs.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Legal and regulatory retention obligations.</span></li>
                    </ul>
                    <p>When information is no longer required, we will take reasonable steps to delete it, anonymize it or otherwise securely dispose of it, subject to applicable law.</p>
                    <p>Deleting an account may not immediately remove transaction records that we are legally required or otherwise lawfully permitted to retain.</p>
                </section>

                <hr class="border-line">

                <!-- 9. International Data Transfers -->
                <section id="privacy-9" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">09</span>
                        International Data Transfers
                    </h2>
                    <p>Some of our technology or service providers may store or process information outside Kenya.</p>
                    <p>Where personal information is transferred outside Kenya, we will take steps to ensure that the transfer is supported by an applicable lawful basis and appropriate safeguards under Kenyan data protection law.</p>
                </section>

                <hr class="border-line">

                <!-- 10. Your Data Protection Rights -->
                <section id="privacy-10" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">10</span>
                        Your Data Protection Rights
                    </h2>
                    <p>Subject to applicable law, you may have the right to:</p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Be informed about how your personal information is used.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Request access to personal information we hold about you.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Request correction of inaccurate or misleading information.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Request deletion of personal information where legally permitted.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Object to certain processing activities.</span></li>
                        <li class="flex items-start gap-2.5"><span class="w-1.5 h-1.5 rounded-full bg-accent mt-2 shrink-0"></span><span>Exercise other rights available under applicable data protection laws.</span></li>
                    </ul>
                    <p>To exercise these rights, contact us using the details in <a href="#privacy-14" class="text-accent font-bold hover:underline">Section 14</a>. We may need to verify your identity before responding to a request. Some information may need to be retained where required by law or where another lawful basis applies.</p>
                </section>

                <hr class="border-line">

                <!-- 11. Third-Party Websites and Services -->
                <section id="privacy-11" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">11</span>
                        Third-Party Websites and Services
                    </h2>
                    <p>ShikaTicket may contain links to external websites or integrate with third-party services.</p>
                    <p>We do not control the privacy practices of independent third parties. You should review their privacy policies before providing personal information to them.</p>
                    <p>Your use of third-party payment, authentication or other services may also be subject to their own terms and privacy notices.</p>
                </section>

                <hr class="border-line">

                <!-- 12. Children's Privacy -->
                <section id="privacy-12" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">12</span>
                        Children's Privacy
                    </h2>
                    <p>ShikaTicket is not designed to encourage children to provide personal information without appropriate authorization.</p>
                    <p>Some events may have age restrictions or other admission requirements. Users and organizers are responsible for observing applicable age requirements and obtaining any required parental or guardian involvement.</p>
                    <p>Where we become aware that personal information has been collected unlawfully from a child, we will take appropriate steps in accordance with applicable law.</p>
                </section>

                <hr class="border-line">

                <!-- 13. Changes to This Privacy Policy -->
                <section id="privacy-13" class="scroll-mt-28 space-y-3">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">13</span>
                        Changes to This Privacy Policy
                    </h2>
                    <p>We may update this Privacy Policy to reflect changes in our services, technology, data practices or legal obligations.</p>
                    <p>Where appropriate, we will notify users of material changes through the platform or other suitable communication channels.</p>
                    <p>The latest version will display its effective date and last updated date.</p>
                </section>

                <hr class="border-line">

                <!-- 14. Contact Us and Privacy Requests -->
                <section id="privacy-14" class="scroll-mt-28 space-y-4">
                    <h2 class="font-display font-bold text-xl text-ink flex items-center gap-2.5">
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-md bg-accent/10 text-accent">14</span>
                        Contact Us and Privacy Requests
                    </h2>
                    <p>For questions, complaints or requests relating to your personal information, contact:</p>
                    
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
                            <p class="text-muted text-xs font-medium">Privacy Email</p>
                            <p class="font-bold text-accent mt-0.5">[PRIVACY CONTACT EMAIL]</p>
                        </div>
                        <div>
                            <p class="text-muted text-xs font-medium">Support Email</p>
                            <p class="font-bold text-accent mt-0.5">[SUPPORT EMAIL]</p>
                        </div>
                        <div>
                            <p class="text-muted text-xs font-medium">Address</p>
                            <p class="font-bold text-ink mt-0.5">[BUSINESS ADDRESS, IF APPLICABLE]</p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-dark text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-bold uppercase tracking-wider text-accent">Regulatory Oversight</p>
                            <p class="text-xs sm:text-sm text-[#c9c9cc]">
                                You may also contact the Office of the Data Protection Commissioner in Kenya if you have concerns about the handling of your personal information.
                            </p>
                        </div>
                        <a href="https://www.odpc.go.ke/" target="_blank" rel="noopener noreferrer" class="shrink-0 inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-white bg-accent hover:bg-accent-dark rounded-xl transition">
                            Visit ODPC Website ↗
                        </a>
                    </div>
                </section>

            </div>
        </div>
    </div>
</div>
@endsection