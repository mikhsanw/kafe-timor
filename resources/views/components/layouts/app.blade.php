<!DOCTYPE html>
<html lang="id" class="h-full bg-stone-100 selection:bg-amber-600 selection:text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Kedai Kopi Timor - Web Pemesanan dari Meja' }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full font-sans antialiased text-stone-800 bg-gradient-to-b from-stone-50 via-stone-100/70 to-stone-200/50 flex flex-col justify-between">
    <div class="min-h-full flex flex-col w-full">
        <!-- Top App Navigation Bar (Sticky Glassmorphic) -->
        <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-stone-200/80 shadow-xs transition duration-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Brand with Animated Steam Coffee Icon -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="relative w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-700 via-amber-800 to-amber-950 text-white flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                        <span class="text-xl">☕</span>
                        <!-- Steam indicators -->
                        <span class="absolute -top-1 left-2.5 w-1 h-2 bg-amber-300/80 rounded-full animate-steam-1"></span>
                        <span class="absolute -top-1.5 left-5 w-1 h-2.5 bg-amber-300/80 rounded-full animate-steam-2"></span>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-extrabold text-stone-900 tracking-tight text-base sm:text-lg group-hover:text-amber-800 transition">
                                Kedai Kopi Timor
                            </span>
                            <span class="hidden md:inline-flex px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 rounded-full">
                                Kupang • NTT
                            </span>
                        </div>
                        <p class="text-[11px] text-stone-500 font-medium hidden sm:block">Pemesanan Mandiri dari Meja</p>
                    </div>
                </a>

                <!-- Right Header Actions (Meja Active Pill & Admin Link) -->
                <div class="flex items-center space-x-2.5 sm:space-x-3">
                    @if(isset($table))
                        <div class="flex items-center space-x-2 px-3 py-1.5 bg-gradient-to-r from-amber-50 to-amber-100/80 border border-amber-300/80 rounded-full text-xs font-bold text-amber-900 shadow-xs animate-pulse-glow">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-600"></span>
                            </span>
                            <span>{{ $table->name }}</span>
                        </div>
                    @endif

                    <a href="/admin" 
                       target="_blank"
                       class="text-xs font-semibold text-stone-500 hover:text-amber-800 px-3 py-1.5 rounded-full hover:bg-stone-200/60 border border-transparent hover:border-stone-200 transition duration-150 flex items-center space-x-1"
                       title="Buka Panel Staf / Admin">
                        <span>Panel Staf</span>
                        <span class="text-[10px]">↗</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Fullscreen Content Area -->
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8 pb-32">
            {{ $slot }}
        </main>

        <!-- Elegant App Footer -->
        <footer class="w-full border-t border-stone-200/80 bg-white/60 backdrop-blur-md py-6 text-center text-xs text-stone-400">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="flex items-center space-x-1.5 font-medium text-stone-500">
                    <span>☕</span>
                    <span>Kedai Kopi Timor — Nikmati Kopi Asli Bajawa & Kuliner Khas Timor</span>
                </div>
                <div>
                    <span>Tanpa antre • Pesan langsung dari meja</span>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
