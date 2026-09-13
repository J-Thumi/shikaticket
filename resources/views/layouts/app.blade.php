<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'ShikaTicket - Go where the good times are' }}</title>

    <link rel="icon" href="{{ asset('images/ticket.png') }}" type="image/x-icon">
    
    <!-- Google Fonts: DM Sans & Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Custom Theme Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'system-ui', 'sans-serif'],
                        display: ['Manrope', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        paper: '#fafaf9',
                        ink: '#121214',
                        muted: '#6d6d73',
                        soft: '#f2f1ef',
                        line: '#e7e7e9',
                        accent: {
                            DEFAULT: '#ff5b3d',
                            dark: '#df4329',
                        },
                        dark: {
                            DEFAULT: '#171719',
                            card: '#121214',
                        }
                    }
                },
            },
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-paper text-ink font-sans leading-relaxed">

    <!-- Navigation Header -->
    <nav x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }" 
         class="h-20 border-b border-line/60 bg-paper/90 backdrop-blur-md sticky top-0 z-40 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">
            
            <!-- Brand Logo & Navigation Links -->
            <div class="flex items-center gap-10">
                <a href="{{ route('events.index') }}" class="group flex items-center gap-1 focus:outline-none">
                    <span class="font-display font-extrabold text-2xl tracking-tight text-ink">
                        ShikaTicket<span class="inline-block w-2 h-2 rounded-full bg-accent ml-0.5"></span>
                    </span>
                </a>

                <!-- Primary Desktop Links -->
                <div class="hidden md:flex items-center space-x-7 text-sm font-medium text-[#4d4d52]">
                    <a href="{{ route('events.index') }}" class="hover:text-ink transition-colors">
                        Explore Events
                    </a>
                    <a href="#" class="hover:text-ink transition-colors">
                        Categories
                    </a>
                    <a href="#" class="hover:text-ink transition-colors">
                        Locations
                    </a>
                    <a href="#" class="hover:text-ink transition-colors">
                        My Tickets
                    </a>
                </div>
            </div>

            <!-- Desktop Navigation Actions -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    {{-- Visible ONLY to Organizers --}}
                    @if (Auth::user()->organizer)
                        <a href="{{ route('organizer.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 text-xs font-bold text-ink bg-white border border-line hover:bg-soft rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('scanner.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-ink bg-soft hover:bg-line rounded-xl transition">
                            <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Entry Scanner
                        </a>
                    @endif

                    <!-- User Profile Dropdown -->
                    <div class="relative" @click.away="profileDropdownOpen = false">
                        <button @click="profileDropdownOpen = !profileDropdownOpen" class="flex items-center gap-2 px-3 py-2 rounded-xl border border-line bg-white hover:bg-soft text-ink transition focus:outline-none">
                            <div class="w-7 h-7 rounded-lg bg-accent/10 border border-accent/20 flex items-center justify-center text-xs font-bold text-accent uppercase">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="text-xs font-bold max-w-[100px] truncate">{{ Auth::user()->name ?? 'Account' }}</span>
                            <svg class="w-3.5 h-3.5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="profileDropdownOpen" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-52 py-2 bg-white border border-line rounded-2xl shadow-xl z-50">
                            <div class="px-4 py-2 border-b border-line">
                                <p class="text-[11px] font-medium text-muted">Signed in as</p>
                                <p class="text-xs font-bold text-ink truncate">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                            @if (Auth::user()->organizer)
                                <a href="{{ route('organizer.dashboard') }}" class="block px-4 py-2.5 text-xs font-bold text-ink hover:bg-soft transition">
                                    Organizer Workspace
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2.5 text-sm font-bold text-ink hover:bg-soft rounded-xl transition">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-accent hover:bg-accent-dark rounded-xl shadow-sm hover:-translate-y-0.5 transition duration-150">
                        Get Started
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg text-ink hover:bg-soft focus:outline-none">
                    <svg class="w-6 h-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="w-6 h-6" x-show="mobileMenuOpen" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-b border-line bg-paper px-4 pt-3 pb-5 space-y-3 shadow-lg">
            <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-lg text-sm font-bold text-ink hover:bg-soft">Explore Events</a>
            <a href="#" class="block px-3 py-2 rounded-lg text-sm font-bold text-ink hover:bg-soft">Categories</a>
            <a href="#" class="block px-3 py-2 rounded-lg text-sm font-bold text-ink hover:bg-soft">Locations</a>
            
            @auth
                @if (Auth::user()->organizer)
                    <a href="{{ route('organizer.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-bold text-ink hover:bg-soft">
                        Organizer Dashboard
                    </a>
                    <a href="{{ route('scanner.index') }}" class="block px-3 py-2 rounded-lg text-sm font-bold text-accent hover:bg-soft">
                        Entry Scanner
                    </a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-line">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-bold text-rose-600 hover:bg-rose-50">
                        Sign out
                    </button>
                </form>
            @else
                <div class="pt-3 border-t border-line flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl text-sm font-bold text-ink bg-soft">Log in</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl text-sm font-bold text-white bg-accent">Get Started</a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Main Content Slot Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-16 pb-8 border-t border-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 pb-12 border-b border-[#2d2d31]">
                
                <!-- Brand Column -->
                <div class="md:col-span-2 space-y-4">
                    <a href="#" class="font-display font-extrabold text-2xl tracking-tight text-white inline-block">
                        ShikaTicket<span class="inline-block w-2 h-2 rounded-full bg-accent ml-0.5"></span>
                    </a>
                    <p class="text-xs text-[#929298] max-w-xs leading-relaxed">
                        A simple way to discover, book, and experience great events with high-concurrency ticket delivery.
                    </p>
                    <div class="flex gap-2 pt-2">
                        <a href="#" class="w-8 h-8 rounded-full border border-[#36363a] flex items-center justify-center text-xs text-white hover:border-white transition">ig</a>
                        <a href="#" class="w-8 h-8 rounded-full border border-[#36363a] flex items-center justify-center text-xs text-white hover:border-white transition">x</a>
                        <a href="#" class="w-8 h-8 rounded-full border border-[#36363a] flex items-center justify-center text-xs text-white hover:border-white transition">fb</a>
                    </div>
                </div>

                <!-- Navigation Columns -->
                <div>
                    <h4 class="text-xs font-bold text-[#85858b] uppercase tracking-wider mb-4">Discover</h4>
                    <ul class="space-y-2.5 text-xs text-[#c9c9cc]">
                        <li><a href="{{ route('events.index') }}" class="hover:text-white transition">Browse events</a></li>
                        <li><a href="#" class="hover:text-white transition">Featured events</a></li>
                        <li><a href="#" class="hover:text-white transition">Categories</a></li>
                        <li><a href="#" class="hover:text-white transition">Popular locations</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-[#85858b] uppercase tracking-wider mb-4">Organizers</h4>
                    <ul class="space-y-2.5 text-xs text-[#c9c9cc]">
                        <li><a href="#" class="hover:text-white transition">Create an event</a></li>
                        <li><a href="{{ route('organizer.dashboard') }}" class="hover:text-white transition">Organizer portal</a></li>
                        <li><a href="#" class="hover:text-white transition">Ticket management</a></li>
                        <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-[#85858b] uppercase tracking-wider mb-4">Support & Legal</h4>
                    <ul class="space-y-2.5 text-xs text-[#c9c9cc]">
                        <li><a href="#" class="hover:text-white transition">Help center</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact us</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-[#77777d] gap-4">
                <p>&copy; {{ date('Y') }} ShikaTicket. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-white transition">Privacy</a>
                    <a href="#" class="hover:text-white transition">Terms</a>
                    <a href="#" class="hover:text-white transition">Cookies</a>
                    <span>Made for events. Built for speed.</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>