<div wire:poll.5s class="max-w-3xl mx-auto space-y-6 sm:space-y-8 animate-fade-scale">
    <!-- Status Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-stone-900 via-amber-950 to-stone-900 text-white p-6 sm:p-8 shadow-xl border border-amber-900/40">
        <div class="absolute -right-8 -top-8 w-48 h-48 bg-amber-500/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-amber-500/20 border border-amber-500/30 rounded-full text-xs font-bold text-amber-300 mb-2">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                    <span>📍 {{ $order->table->name }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Status Pesanan Anda</h1>
                <p class="text-xs sm:text-sm text-stone-300 mt-1">
                    Atas nama: <span class="font-bold text-white">{{ $order->customer_name }}</span>
                </p>
            </div>

            <!-- Nomor Pesanan Box -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl flex flex-col items-start sm:items-end">
                <span class="text-[11px] uppercase tracking-wider text-amber-200 font-bold">Nomor Pesanan</span>
                <span class="text-lg sm:text-xl font-mono font-black text-white tracking-wide">{{ $order->order_number }}</span>
                <span class="text-[10px] text-stone-300 mt-0.5">Waktu: {{ $order->created_at->format('H:i') }} WITA</span>
            </div>
        </div>
    </div>

    <!-- Animated Status Tracker Card -->
    @php
        $currentStatus = $order->status;
        $statuses = [
            'baru' => [
                'step' => 1,
                'title' => 'Pesanan Diterima',
                'desc' => 'Pesanan Anda sudah masuk ke sistem antrean dapur / barista.',
                'icon' => '⏳',
                'bg' => 'from-amber-50 to-amber-100/60',
                'border' => 'border-amber-300',
                'text' => 'text-amber-900',
                'badge' => 'bg-amber-100 text-amber-800',
            ],
            'diproses' => [
                'step' => 2,
                'title' => 'Sedang Diracik / Dimasak',
                'desc' => 'Barista dan staf dapur kami sedang meracik pesanan Anda dengan penuh perhatian.',
                'icon' => '👨‍🍳',
                'bg' => 'from-blue-50 to-blue-100/60',
                'border' => 'border-blue-300',
                'text' => 'text-blue-900',
                'badge' => 'bg-blue-100 text-blue-800',
            ],
            'siap' => [
                'step' => 3,
                'title' => 'Pesanan Siap!',
                'desc' => 'Pesanan Anda telah selesai disiapkan dan segera diantar ke meja Anda.',
                'icon' => '🛎️',
                'bg' => 'from-indigo-50 to-indigo-100/60',
                'border' => 'border-indigo-300',
                'text' => 'text-indigo-900',
                'badge' => 'bg-indigo-100 text-indigo-800',
            ],
            'selesai' => [
                'step' => 4,
                'title' => 'Pesanan Selesai',
                'desc' => 'Terima kasih telah berkunjung & menikmati sajian di Kedai Kopi Timor!',
                'icon' => '🎉',
                'bg' => 'from-emerald-50 to-emerald-100/60',
                'border' => 'border-emerald-300',
                'text' => 'text-emerald-900',
                'badge' => 'bg-emerald-100 text-emerald-800',
            ],
            'dibatalkan' => [
                'step' => 0,
                'title' => 'Pesanan Dibatalkan',
                'desc' => 'Pesanan ini telah dibatalkan. Silakan hubungi kasir atau pelayan jika ada kendala.',
                'icon' => '❌',
                'bg' => 'from-rose-50 to-rose-100/60',
                'border' => 'border-rose-300',
                'text' => 'text-rose-900',
                'badge' => 'bg-rose-100 text-rose-800',
            ],
        ];

        $cur = $statuses[$currentStatus] ?? $statuses['baru'];
        $stepIndex = $cur['step'];
    @endphp

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-md space-y-6">
        <!-- Live Status Pill & Header -->
        <div class="flex items-center justify-between">
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $cur['badge'] }} flex items-center space-x-1.5 shadow-2xs">
                <span>{{ $cur['icon'] }}</span>
                <span>{{ ucfirst($order->status) }}</span>
            </span>

            <div class="flex items-center space-x-1.5 text-xs text-stone-400 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Auto-refresh 5 detik</span>
            </div>
        </div>

        <!-- Big Highlight Banner for Current Status -->
        <div class="rounded-2xl p-5 sm:p-6 bg-gradient-to-r {{ $cur['bg'] }} border {{ $cur['border'] }} {{ $cur['text'] }} flex items-start space-x-4 shadow-xs">
            <div class="text-3xl sm:text-4xl shrink-0 animate-bounce">
                {{ $cur['icon'] }}
            </div>
            <div class="space-y-1">
                <h2 class="text-base sm:text-lg font-extrabold leading-snug">
                    {{ $cur['title'] }}
                </h2>
                <p class="text-xs sm:text-sm opacity-90 leading-relaxed">
                    {{ $cur['desc'] }}
                </p>
            </div>
        </div>

        <!-- Animated Stepper Bar (Jika tidak dibatalkan) -->
        @if($currentStatus !== 'dibatalkan')
            <div class="py-4">
                <div class="relative">
                    <!-- Progress Bar Track -->
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-stone-100">
                        @php
                            $width = match($stepIndex) {
                                1 => '25%',
                                2 => '50%',
                                3 => '75%',
                                4 => '100%',
                                default => '10%',
                            };
                        @endphp
                        <div style="width: {{ $width }}" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-amber-600 via-amber-700 to-amber-900 transition-all duration-700 ease-out"></div>
                    </div>

                    <!-- Steps Dots & Labels -->
                    <div class="grid grid-cols-4 gap-1 text-center">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center space-y-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 {{ $stepIndex >= 1 ? 'bg-amber-800 text-white shadow-md' : 'bg-stone-200 text-stone-500' }}">
                                {{ $stepIndex > 1 ? '✓' : '1' }}
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold {{ $stepIndex >= 1 ? 'text-amber-950' : 'text-stone-400' }}">
                                Diterima
                            </span>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex flex-col items-center space-y-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 {{ $stepIndex >= 2 ? 'bg-amber-800 text-white shadow-md' : 'bg-stone-200 text-stone-500' }}">
                                {{ $stepIndex > 2 ? '✓' : '2' }}
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold {{ $stepIndex >= 2 ? 'text-amber-950' : 'text-stone-400' }}">
                                Diproses
                            </span>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex flex-col items-center space-y-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 {{ $stepIndex >= 3 ? 'bg-amber-800 text-white shadow-md' : 'bg-stone-200 text-stone-500' }}">
                                {{ $stepIndex > 3 ? '✓' : '3' }}
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold {{ $stepIndex >= 3 ? 'text-amber-950' : 'text-stone-400' }}">
                                Siap
                            </span>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex flex-col items-center space-y-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 {{ $stepIndex >= 4 ? 'bg-emerald-600 text-white shadow-md' : 'bg-stone-200 text-stone-500' }}">
                                {{ $stepIndex >= 4 ? '✓' : '4' }}
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold {{ $stepIndex >= 4 ? 'text-emerald-800' : 'text-stone-400' }}">
                                Selesai
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Rincian Menu Pesanan Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-md space-y-5">
        <h3 class="font-extrabold text-stone-900 text-base sm:text-lg border-b border-stone-100 pb-3 flex items-center justify-between">
            <span>Rincian Item Menu</span>
            <span class="text-xs font-medium text-stone-500">{{ $order->orderItems->count() }} macam item</span>
        </h3>

        <div class="divide-y divide-stone-100 space-y-3">
            @foreach($order->orderItems as $item)
                <div class="pt-3 first:pt-0 flex items-center justify-between text-xs sm:text-sm">
                    <div class="space-y-0.5">
                        <div class="font-bold text-stone-900 flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded-lg bg-stone-100 text-stone-700 font-extrabold text-xs">
                                {{ $item->quantity }}x
                            </span>
                            <span>{{ $item->menu?->name ?? 'Menu' }}</span>
                        </div>
                        <div class="text-[11px] text-stone-500 pl-8">
                            {{ $item->formatted_unit_price }} / porsi
                        </div>
                    </div>
                    <div class="font-extrabold text-amber-950 text-sm">
                        {{ $item->formatted_subtotal }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4 border-t-2 border-stone-100 flex justify-between items-center bg-stone-50 -mx-6 sm:-mx-8 -mb-6 sm:-mb-8 p-6 rounded-b-3xl">
            <div>
                <span class="text-xs text-stone-500 font-medium block">Total Pembayaran:</span>
                <span class="text-xl sm:text-2xl font-black text-amber-950">{{ $order->formatted_total }}</span>
            </div>

            <div class="text-right text-xs text-stone-500">
                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-900 font-bold rounded-xl">
                    💳 Bayar di Kasir
                </span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 pt-2">
        <a href="{{ route('table.menu', ['kode' => $order->table->code]) }}" 
           class="flex-1 py-3.5 px-6 rounded-2xl bg-amber-800 hover:bg-amber-900 active:scale-98 text-white font-bold text-xs sm:text-sm shadow-md transition duration-150 text-center flex items-center justify-center space-x-2">
            <span>+ Pesan Menu Lain dari {{ $order->table->name }}</span>
        </a>

        <a href="{{ route('home') }}" 
           class="py-3.5 px-6 rounded-2xl bg-white hover:bg-stone-50 active:scale-98 text-stone-700 font-bold text-xs sm:text-sm border border-stone-300 shadow-2xs transition duration-150 text-center">
            Pindah Meja / Halaman Awal
        </a>
    </div>
</div>
