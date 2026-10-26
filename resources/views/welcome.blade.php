<!DOCTYPE html>
<html lang="id" class="h-full bg-stone-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kedai Kopi Timor - Web Pemesanan dari Meja</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-stone-800 bg-stone-50 flex flex-col justify-center items-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl p-6 border border-stone-200/90 shadow-xl space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-amber-800 text-white items-center justify-center text-3xl shadow-md">
                ☕
            </div>
            <h1 class="text-2xl font-extrabold text-stone-900 tracking-tight">Kedai Kopi Timor</h1>
            <p class="text-xs text-stone-500 max-w-xs mx-auto leading-relaxed">
                Sistem pemesanan makanan & minuman langsung dari meja via scan QR code tanpa antre.
            </p>
        </div>

        <!-- Pilih Meja Simulasi QR -->
        <div class="space-y-3">
            <div class="flex items-center justify-between text-xs font-bold text-stone-700">
                <span>📱 Simulasi Scan QR Meja:</span>
                <span class="text-stone-400 font-normal">{{ $tables->count() }} meja tersedia</span>
            </div>

            <div class="grid grid-cols-1 gap-2">
                @foreach($tables as $table)
                    <a href="{{ route('table.menu', ['kode' => $table->code]) }}" 
                       class="group p-3 rounded-2xl border border-stone-200 hover:border-amber-500 hover:bg-amber-50/50 flex items-center justify-between transition shadow-2xs">
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-xl bg-stone-100 group-hover:bg-amber-200 text-stone-700 group-hover:text-amber-900 flex items-center justify-center text-xs font-bold transition">
                                {{ $loop->iteration }}
                            </span>
                            <div>
                                <div class="text-xs font-bold text-stone-900">{{ $table->name }}</div>
                                <div class="text-[11px] text-stone-500">/meja/{{ $table->code }}</div>
                            </div>
                        </div>
                        <span class="text-amber-800 text-xs font-bold group-hover:translate-x-1 transition">
                            Pesan →
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Panel Admin Shortcut -->
        <div class="pt-2 border-t border-stone-100">
            <a href="/admin" 
               class="w-full py-2.5 px-4 rounded-xl bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold flex items-center justify-center space-x-2 shadow-sm transition">
                <span>⚙️ Masuk Panel Admin (Filament)</span>
            </a>
            <p class="text-[11px] text-center text-stone-400 mt-2">
                Login: <code class="bg-stone-100 px-1 py-0.5 rounded text-stone-600 font-mono">admin@kedai.test</code> | Password: <code class="bg-stone-100 px-1 py-0.5 rounded text-stone-600 font-mono">password</code>
            </p>
        </div>
    </div>
</body>
</html>
