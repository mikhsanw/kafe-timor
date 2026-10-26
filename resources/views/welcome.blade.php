<!DOCTYPE html>
<html lang="id" class="h-full bg-stone-100 selection:bg-amber-600 selection:text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Kedai Kopi Timor - Web Pemesanan dari Meja</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans antialiased text-stone-800 bg-gradient-to-br from-stone-900 via-stone-950 to-amber-950 flex flex-col justify-between p-4 sm:p-8">
    
    <!-- Ambient Lighting Effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-1/4 w-96 h-96 bg-amber-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-amber-800/15 rounded-full blur-3xl"></div>
    </div>

    <!-- Header bar -->
    <header class="relative z-10 w-full max-w-5xl mx-auto flex items-center justify-between py-4">
        <div class="flex items-center space-x-3">
            <div class="relative w-10 h-10 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl shadow-lg">
                ☕
                <span class="absolute -top-1 left-2 w-1 h-2 bg-amber-300 rounded-full animate-steam-1"></span>
                <span class="absolute -top-1.5 left-5 w-1 h-2.5 bg-amber-300 rounded-full animate-steam-2"></span>
            </div>
            <span class="font-extrabold text-white text-lg tracking-tight">Kedai Kopi Timor</span>
        </div>

        <a href="/admin" 
           class="px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 backdrop-blur-md transition duration-200 flex items-center space-x-1.5 shadow-sm">
            <span>Panel Staf / Admin</span>
            <span>↗</span>
        </a>
    </header>

    <!-- Main Card Container -->
    <main class="relative z-10 w-full max-w-5xl mx-auto my-auto py-8">
        <div class="bg-white/95 backdrop-blur-2xl rounded-3xl p-6 sm:p-10 border border-white/20 shadow-2xl space-y-8 animate-fade-scale">
            
            <!-- Hero Title -->
            <div class="text-center space-y-3 max-w-2xl mx-auto">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900">
                    <span>✨ Web Pemesanan Mandiri dari Meja</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-stone-900 tracking-tight leading-tight">
                    Pilih Meja & Mulai Memesan
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 leading-relaxed">
                    Di kedai fisik, pelanggan cukup memindai QR code di atas meja untuk membuka menu dan memesan tanpa harus mengantre di kasir.
                </p>
            </div>

            <!-- Grid Simulasi Meja (Fullscreen Responsive) -->
            <div class="space-y-4">
                <div class="flex items-center justify-between text-xs font-bold text-stone-700 border-b border-stone-200 pb-2">
                    <span class="flex items-center space-x-2">
                        <span>📱</span>
                        <span>Simulasi Scan QR Meja (Pilih salah satu):</span>
                    </span>
                    <span class="text-stone-400 font-medium">{{ $tables->count() }} meja tersedia</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($tables as $table)
                        <a href="{{ route('table.menu', ['kode' => $table->code]) }}" 
                           class="group relative bg-stone-50 hover:bg-gradient-to-br hover:from-amber-50 hover:to-amber-100/60 p-5 rounded-2xl border border-stone-200/90 hover:border-amber-400 transition-all duration-200 shadow-2xs hover:shadow-md flex items-center justify-between">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-12 h-12 rounded-xl bg-white group-hover:bg-amber-600 group-hover:text-white text-stone-800 border border-stone-200 group-hover:border-amber-600 flex items-center justify-center font-black text-base shadow-2xs transition-all duration-200">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-stone-900 text-sm group-hover:text-amber-950 transition-colors">
                                        {{ $table->name }}
                                    </h3>
                                    <p class="text-xs text-stone-400 font-mono mt-0.5">/meja/{{ $table->code }}</p>
                                </div>
                            </div>

                            <div class="w-8 h-8 rounded-full bg-white group-hover:bg-amber-700 text-stone-400 group-hover:text-white flex items-center justify-center font-bold text-sm shadow-2xs group-hover:translate-x-1 transition-all duration-200">
                                →
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Admin Notice Banner -->
            <div class="bg-stone-100/80 rounded-2xl p-4 sm:p-5 border border-stone-200/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">👨‍🍳</span>
                    <div>
                        <div class="font-bold text-stone-800">Panel Manajemen Dapur & Kasir</div>
                        <div class="text-stone-500 text-[11px]">
                            Login: <span class="font-mono font-semibold text-stone-700">admin@kedai.test</span> • Password: <span class="font-mono font-semibold text-stone-700">password</span>
                        </div>
                    </div>
                </div>

                <a href="/admin" 
                   class="px-5 py-2.5 bg-stone-900 hover:bg-stone-800 active:scale-95 text-white font-bold rounded-xl shadow-xs transition duration-150 shrink-0">
                    Buka Panel Admin →
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full max-w-5xl mx-auto text-center py-4 text-xs text-stone-400">
        <p>© Kedai Kopi Timor — Cita rasa kopi asli Bajawa & makanan khas Nusa Tenggara Timur.</p>
    </footer>

</body>
</html>
