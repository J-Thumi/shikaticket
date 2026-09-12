<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-paper text-ink">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Organizer Portal - ShikaTicket' }}</title>
    <link rel="icon" href="{{ asset('images/ticket.png') }}" type="image/x-icon">

    <!-- Google Fonts & Tailwind Play CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        paper: '#fafaf9',
                        soft: '#f5f5f4',
                        line: '#e7e5e4',
                        ink: '#121214',
                        muted: '#78716c',
                        accent: {
                            DEFAULT: '#ff5b3d',
                            dark: '#e0482b',
                        }
                    },
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                        display: ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full flex flex-col font-sans antialiased bg-paper text-ink">
    <!-- Navbar -->
    <header class="border-b border-line bg-white sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <a href="{{ route('organizer.dashboard') }}" class="font-display text-lg font-extrabold tracking-tight text-ink flex items-center gap-2">
                    <span class="bg-accent text-white p-1.5 rounded-xl shadow-sm text-sm">🎟️</span>
                    <span>Shika<span class="text-accent">Ticket</span></span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-soft text-muted border border-line hidden sm:inline-block">Organizer</span>
                </a>
                <nav class="hidden md:flex space-x-1">
                    <a href="{{ route('organizer.dashboard') }}" class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('organizer.dashboard') ? 'bg-paper text-ink border border-line shadow-sm' : 'text-muted hover:bg-soft hover:text-ink' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('organizer.events.index') }}" class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('organizer.events.*') ? 'bg-paper text-ink border border-line shadow-sm' : 'text-muted hover:bg-soft hover:text-ink' }}">
                        Events Management
                    </a>
                </nav>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-xs font-bold px-3 py-1.5 rounded-xl bg-accent/5 text-accent border border-accent/20">
                    {{ auth()->user()->organizer->name ?? auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-muted hover:text-ink transition">Sign Out</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('status'))
            <div class="mb-6 flex items-center gap-3 p-4 text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl">
                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-line bg-white py-6 text-center text-xs font-medium text-muted">
        &copy; {{ date('Y') }} ShikaTicket Engine. Event Organizer Management Workspace.
    </footer>
</body>
</html>