<!DOCTYPE html>
<html lang="id" class="h-full bg-stone-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Kedai Kopi Timor' }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-stone-800 bg-stone-50 flex flex-col justify-between selection:bg-amber-500 selection:text-white">
    <div class="min-h-full flex flex-col">
        <!-- Top App Navigation Bar -->
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-stone-200 shadow-xs">
            <div class="max-w-md mx-auto px-4 h-14 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">☕</span>
                    <span class="font-bold text-stone-900 tracking-tight text-lg">Kedai Kopi Timor</span>
                </div>
                @if(isset($table))
                    <div class="flex items-center space-x-1.5 px-2.5 py-1 bg-amber-50 border border-amber-200/80 rounded-full text-xs font-semibold text-amber-800 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>{{ $table->name }}</span>
                    </div>
                @endif
            </div>
        </header>

        <!-- Main Content Area (Max width phone viewport friendly) -->
        <main class="flex-1 max-w-md w-full mx-auto pb-24">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
