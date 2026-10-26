<div wire:poll.5s class="px-4 py-5 space-y-6">
    <!-- Status Banner Card -->
    <div class="bg-white rounded-2xl p-5 border border-stone-200/90 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold px-2.5 py-1 bg-stone-100 text-stone-600 rounded-full">
                {{ $order->table->name }}
            </span>
            <div class="flex items-center space-x-1.5 text-xs text-stone-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Live update 5 detik</span>
            </div>
        </div>

        <div>
            <div class="text-xs text-stone-500 font-medium">Nomor Pesanan</div>
            <div class="text-lg font-extrabold text-stone-900 tracking-tight">{{ $order->order_number }}</div>
            <div class="text-xs text-stone-600 mt-0.5">Atas nama: <span class="font-bold text-stone-900">{{ $order->customer_name }}</span></div>
        </div>

        <!-- Status Visual Tracker -->
        @php
            $currentStatus = $order->status;
            $statuses = [
                'baru' => ['label' => 'Pesanan Diterima', 'desc' => 'Pesanan Anda sudah masuk ke sistem kedai.', 'color' => 'amber'],
                'diproses' => ['label' => 'Sedang Diracik / Dimasak', 'desc' => 'Barista & dapur kami sedang menyiapkan pesanan Anda.', 'color' => 'blue'],
                'siap' => ['label' => 'Pesanan Siap!', 'desc' => 'Pesanan Anda telah siap untuk diantar/disajikan.', 'color' => 'indigo'],
                'selesai' => ['label' => 'Pesanan Selesai', 'desc' => 'Terima kasih telah memesan di Kedai Kopi Timor!', 'color' => 'emerald'],
                'dibatalkan' => ['label' => 'Pesanan Dibatalkan', 'desc' => 'Pesanan ini telah dibatalkan oleh kasir/admin.', 'color' => 'rose'],
            ];
            $info = $statuses[$currentStatus] ?? ['label' => ucfirst($currentStatus), 'desc' => '', 'color' => 'stone'];
        @endphp

        <div class="p-4 rounded-xl @if($currentStatus === 'baru') bg-amber-50 border border-amber-200 text-amber-900 @elseif($currentStatus === 'diproses') bg-blue-50 border border-blue-200 text-blue-900 @elseif($currentStatus === 'siap') bg-indigo-50 border border-indigo-200 text-indigo-900 @elseif($currentStatus === 'selesai') bg-emerald-50 border border-emerald-200 text-emerald-900 @else bg-rose-50 border border-rose-200 text-rose-900 @endif">
            <div class="flex items-center space-x-2">
                <span class="text-xl">
                    @if($currentStatus === 'baru') ⏳ @elseif($currentStatus === 'diproses') 👨‍🍳 @elseif($currentStatus === 'siap') 🛎️ @elseif($currentStatus === 'selesai') ✅ @else ❌ @endif
                </span>
                <span class="font-bold text-sm">{{ $info['label'] }}</span>
            </div>
            <p class="text-xs mt-1 leading-relaxed opacity-90">{{ $info['desc'] }}</p>
        </div>

        <!-- Alur Timeline Status -->
        @if($currentStatus !== 'dibatalkan')
            <div class="pt-2">
                <div class="flex items-center justify-between text-[11px] font-bold text-stone-400">
                    <span class="@if(in_array($currentStatus, ['baru', 'diproses', 'siap', 'selesai'])) text-amber-800 font-bold @endif">1. Masuk</span>
                    <span>→</span>
                    <span class="@if(in_array($currentStatus, ['diproses', 'siap', 'selesai'])) text-blue-700 font-bold @endif">2. Diproses</span>
                    <span>→</span>
                    <span class="@if(in_array($currentStatus, ['siap', 'selesai'])) text-indigo-700 font-bold @endif">3. Siap</span>
                    <span>→</span>
                    <span class="@if($currentStatus === 'selesai') text-emerald-700 font-bold @endif">4. Selesai</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Rincian Pesanan Card -->
    <div class="bg-white rounded-2xl p-5 border border-stone-200/90 shadow-xs space-y-4">
        <h3 class="font-bold text-stone-900 text-sm border-b border-stone-100 pb-2">Rincian Menu</h3>

        <div class="divide-y divide-stone-100 space-y-2">
            @foreach($order->orderItems as $item)
                <div class="pt-2 first:pt-0 flex justify-between items-start text-xs">
                    <div>
                        <div class="font-semibold text-stone-800">
                            {{ $item->quantity }}x {{ $item->menu?->name ?? 'Menu Dihapus' }}
                        </div>
                        <div class="text-[11px] text-stone-500">
                            {{ $item->formatted_unit_price }}
                        </div>
                    </div>
                    <div class="font-bold text-stone-900">
                        {{ $item->formatted_subtotal }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-3 border-t border-stone-200 flex justify-between items-center">
            <span class="font-bold text-stone-700 text-xs">Total Pembayaran:</span>
            <span class="text-base font-extrabold text-amber-950">{{ $order->formatted_total }}</span>
        </div>
    </div>

    <!-- Info Pembayaran -->
    <div class="bg-amber-50/70 border border-amber-200/70 rounded-2xl p-4 text-xs text-amber-900/90 space-y-1">
        <div class="font-bold flex items-center space-x-1">
            <span>ℹ️</span>
            <span>Informasi Pembayaran</span>
        </div>
        <p class="leading-relaxed text-[11px]">
            Silakan melakukan pembayaran di kasir dengan menyebutkan <span class="font-bold">Nomor Pesanan</span> atau <span class="font-bold">{{ $order->table->name }}</span>.
        </p>
    </div>

    <!-- Tombol Navigasi Tambahan -->
    <div class="pt-2 text-center">
        <a href="{{ route('table.menu', ['kode' => $order->table->code]) }}" 
           class="inline-flex items-center justify-center space-x-2 w-full py-3 bg-stone-900 hover:bg-stone-800 active:scale-98 text-white text-xs font-bold rounded-xl shadow-xs transition">
            <span>+ Pesan Menu Lain dari {{ $order->table->name }}</span>
        </a>
    </div>
</div>
