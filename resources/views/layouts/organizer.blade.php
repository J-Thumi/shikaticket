<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Organizer Portal - ShikaTicket' }}</title>

    <link rel="icon" href="{{ asset('images/ticket.png') }}" type="image/x-icon">

    <!-- Google Fonts: DM Sans & Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Matching Theme Tokens -->
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

    <!-- Organizer Navigation Header -->
    <nav x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }" 
         class="h-20 border-b border-line/60 bg-paper/90 backdrop-blur-md sticky top-0 z-40 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">
            
            <!-- Brand & Organizer Navigation Links -->
            <div class="flex items-center gap-8">
                <a href="{{ route('organizer.dashboard') }}" class="group flex items-center gap-2 focus:outline-none">
                    <span class="font-display font-extrabold text-2xl tracking-tight text-ink">
                        ShikaTicket<span class="inline-block w-2 h-2 rounded-full bg-accent ml-0.5"></span>
                    </span>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-full bg-soft text-muted border border-line">
                        Organizer
                    </span>
                </a>

                <!-- Primary Workspace Navigation -->
                <div class="hidden md:flex items-center space-x-2 text-sm font-semibold">
                    <a href="{{ route('organizer.dashboard') }}" 
                       class="px-4 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('organizer.dashboard') ? 'bg-white text-ink border border-line shadow-sm' : 'text-muted hover:text-ink hover:bg-soft' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('organizer.events.index') }}" 
                       class="px-4 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('organizer.events.*') ? 'bg-white text-ink border border-line shadow-sm' : 'text-muted hover:text-ink hover:bg-soft' }}">
                        Events Management
                    </a>
                </div>
            </div>

            <!-- Right Side Controls & Profile -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('events.index') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-muted hover:text-ink bg-white border border-line rounded-xl hover:bg-soft transition shadow-sm">
                    <span>View Public Portal</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>

                <!-- Organizer Profile Dropdown -->
                <div class="relative" @click.away="profileDropdownOpen = false">
                    <button @click="profileDropdownOpen = !profileDropdownOpen" class="flex items-center gap-2.5 px-3 py-2 rounded-xl border border-line bg-white hover:bg-soft text-ink transition focus:outline-none shadow-sm">
                        <div class="w-7 h-7 rounded-lg bg-accent/10 border border-accent/20 flex items-center justify-center text-xs font-bold text-accent uppercase">
                            {{ substr(auth()->user()->organizer->name ?? auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <span class="text-xs font-bold block max-w-[120px] truncate leading-tight">
                                {{ auth()->user()->organizer->name ?? auth()->user()->name }}
                            </span>
                        </div>
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
                         class="absolute right-0 mt-2 w-56 py-2 bg-white border border-line rounded-2xl shadow-xl z-50">
                        <div class="px-4 py-2.5 border-b border-line">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-muted">Organizer Account</p>
                            <p class="text-xs font-bold text-ink truncate mt-0.5">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
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

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-b border-line bg-paper px-4 pt-3 pb-5 space-y-2 shadow-lg">
            <a href="{{ route('organizer.dashboard') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('organizer.dashboard') ? 'bg-white text-ink border border-line' : 'text-muted hover:bg-soft' }}">
                Dashboard
            </a>
            <a href="{{ route('organizer.events.index') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('organizer.events.*') ? 'bg-white text-ink border border-line' : 'text-muted hover:bg-soft' }}">
                Events Management
            </a>
            <a href="{{ route('events.index') }}" target="_blank" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-accent hover:bg-soft">
                View Public Portal →
            </a>
            <form method="POST" action="{{ route('logout') }}" class="pt-3 border-t border-line">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50">
                    Sign out
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('status'))
            <div class="mb-6 flex items-center gap-3 p-4 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Dark Contrast Footer (Matches Main App Layout) -->
    <footer class="bg-dark text-white pt-12 pb-8 border-t border-dark mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-8 border-b border-[#2d2d31] gap-6">
                <div class="space-y-1">
                    <a href="{{ route('organizer.dashboard') }}" class="font-display font-extrabold text-2xl tracking-tight text-white inline-block">
                        ShikaTicket<span class="inline-block w-2 h-2 rounded-full bg-accent ml-0.5"></span>
                    </a>
                    <p class="text-xs text-[#929298] max-w-sm">
                        High-performance event management workspace for organizers. Real-time ticket sales, inventory holds, and entrance analytics.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-[#c9c9cc]">
                    <a href="{{ route('organizer.dashboard') }}" class="hover:text-white transition">Dashboard</a>
                    <a href="{{ route('organizer.events.index') }}" class="hover:text-white transition">Events</a>
                    <a href="{{ route('events.index') }}" target="_blank" class="hover:text-white transition">Public Site</a>
                </div>
            </div>

            <div class="pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-[#77777d] gap-4">
                <p>&copy; {{ date('Y') }} ShikaTicket Workspace. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <span>Engine v2.0</span>
                    <span>Built for concurrency and speed.</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>