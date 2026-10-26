<div class="px-4 py-4 space-y-6">
    <!-- Banner Sambutan Meja -->
    <div class="bg-gradient-to-r from-amber-800 to-amber-950 text-white rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-700/60 text-amber-200 border border-amber-500/30 mb-1">
                    {{ $table->name }}
                </span>
                <h1 class="text-xl font-bold tracking-tight">Selamat Datang di Kedai</h1>
                <p class="text-xs text-amber-200/90 mt-0.5">Pesan langsung dari meja tanpa perlu antre ke kasir.</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-700/40 border border-amber-600/30 flex items-center justify-center text-2xl shadow-inner shrink-0">
                ☕
            </div>
        </div>
    </div>

    <!-- Navigasi Kategori (Horizontal Pills) -->
    <div class="sticky top-14 z-30 -mx-4 px-4 py-2 bg-stone-50/95 backdrop-blur-sm border-b border-stone-200/80 overflow-x-auto no-scrollbar flex space-x-2">
        @foreach($categories as $cat)
            <a href="#kategori-{{ $cat->id }}" class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-medium bg-white text-stone-700 border border-stone-200 hover:border-amber-400 hover:text-amber-800 shadow-2xs transition">
                {{ $cat->name }} ({{ $cat->menus->count() }})
            </a>
        @endforeach
    </div>

    @error('cart')
        <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 font-medium">
            ⚠️ {{ $message }}
        </div>
    @enderror

    <!-- Daftar Kategori & Menu -->
    <div class="space-y-8">
        @foreach($categories as $category)
            @if($category->menus->isNotEmpty())
                <section id="kategori-{{ $category->id }}" class="scroll-mt-28 space-y-3">
                    <div class="flex items-center justify-between border-b border-stone-200 pb-2">
                        <h2 class="text-base font-bold text-stone-900 flex items-center space-x-2">
                            <span>{{ $category->name }}</span>
                        </h2>
                        <span class="text-xs text-stone-500 font-medium">{{ $category->menus->count() }} pilihan</span>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        @foreach($category->menus as $menu)
                            <div class="bg-white p-4 rounded-xl border border-stone-200/90 shadow-2xs hover:border-amber-300 transition flex items-start justify-between space-x-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-stone-900 text-sm leading-snug">{{ $menu->name }}</h3>
                                    @if($menu->description)
                                        <p class="text-xs text-stone-500 mt-1 line-clamp-2 leading-relaxed">{{ $menu->description }}</p>
                                    @endif
                                    <div class="mt-2 text-sm font-bold text-amber-900">
                                        {{ $menu->formatted_price }}
                                    </div>
                                </div>

                                <div class="shrink-0 pt-0.5">
                                    @if(isset($cart[$menu->id]) && $cart[$menu->id] > 0)
                                        <div class="flex items-center space-x-1.5 bg-amber-50 border border-amber-300 rounded-xl p-1 shadow-2xs">
                                            <button 
                                                type="button" 
                                                wire:click="decrementQuantity({{ $menu->id }})" 
                                                class="w-7 h-7 rounded-lg bg-white text-stone-700 border border-amber-200 flex items-center justify-center font-bold text-sm hover:bg-amber-100 active:scale-95 transition"
                                                aria-label="Kurangi">
                                                -
                                            </button>
                                            <span class="w-6 text-center text-xs font-bold text-amber-950">
                                                {{ $cart[$menu->id] }}
                                            </span>
                                            <button 
                                                type="button" 
                                                wire:click="incrementQuantity({{ $menu->id }})" 
                                                class="w-7 h-7 rounded-lg bg-amber-700 text-white flex items-center justify-center font-bold text-sm hover:bg-amber-800 active:scale-95 transition shadow-2xs"
                                                aria-label="Tambah">
                                                +
                                            </button>
                                        </div>
                                    @else
                                        <button 
                                            type="button" 
                                            wire:click="addToCart({{ $menu->id }})" 
                                            class="px-3 py-1.5 bg-amber-800 hover:bg-amber-900 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-2xs transition flex items-center space-x-1">
                                            <span>+ Tambah</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach
    </div>

    <!-- Floating Sticky Cart Bar (Muncul bila keranjang ada isinya) -->
    @if($this->totalCount > 0)
        <div class="fixed bottom-3 left-0 right-0 z-40 px-4">
            <div class="max-w-md mx-auto bg-stone-900 text-white p-3.5 rounded-2xl shadow-xl flex items-center justify-between border border-stone-800">
                <div class="flex items-center space-x-3 cursor-pointer" wire:click="toggleCartModal">
                    <div class="relative w-10 h-10 rounded-xl bg-amber-600 flex items-center justify-center text-lg font-bold shadow-xs">
                        🛍️
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center border-2 border-stone-900">
                            {{ $this->totalCount }}
                        </span>
                    </div>
                    <div>
                        <div class="text-xs text-stone-400 leading-tight">{{ $this->totalCount }} item dipilih</div>
                        <div class="text-base font-bold text-amber-400 leading-tight">Rp{{ number_format($this->totalAmount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <button 
                    type="button" 
                    wire:click="toggleCartModal"
                    class="px-4 py-2 bg-amber-500 hover:bg-amber-400 active:scale-95 text-stone-950 text-xs font-bold rounded-xl transition shadow-sm flex items-center space-x-1.5">
                    <span>Lihat Keranjang</span>
                    <span>→</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Bottom Sheet Modal Keranjang & Konfirmasi Pesanan -->
    @if($isCartOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-end justify-center">
            <div class="w-full max-w-md bg-white rounded-t-3xl p-5 shadow-2xl max-h-[90vh] flex flex-col animate-in slide-in-from-bottom duration-200">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                    <div>
                        <h3 class="font-bold text-stone-900 text-base">Keranjang Pesanan</h3>
                        <p class="text-xs text-stone-500">{{ $table->name }} • {{ $this->totalCount }} item</p>
                    </div>
                    <button 
                        type="button" 
                        wire:click="toggleCartModal"
                        class="w-8 h-8 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center hover:bg-stone-200 transition font-bold text-sm">
                        ✕
                    </button>
                </div>

                <!-- Item Keranjang List -->
                <div class="flex-1 overflow-y-auto py-3 space-y-3 divide-y divide-stone-100">
                    @forelse($this->cartItems as $item)
                        <div class="pt-3 first:pt-0 flex items-center justify-between space-x-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-stone-900 text-xs truncate">{{ $item->menu->name }}</h4>
                                <div class="text-[11px] text-stone-500">
                                    {{ $item->menu->formatted_price }} / porsi
                                </div>
                                <div class="text-xs font-bold text-amber-900 mt-0.5">
                                    {{ $item->formatted_subtotal }}
                                </div>
                            </div>

                            <!-- Quantity Controller -->
                            <div class="flex items-center space-x-1 bg-stone-100 rounded-lg p-0.5">
                                <button 
                                    type="button" 
                                    wire:click="decrementQuantity({{ $item->menu->id }})" 
                                    class="w-6 h-6 rounded bg-white text-stone-700 flex items-center justify-center text-xs font-bold shadow-2xs hover:bg-stone-50">
                                    -
                                </button>
                                <input 
                                    type="number" 
                                    min="1" 
                                    max="99" 
                                    value="{{ $item->quantity }}" 
                                    wire:change="updateQuantity({{ $item->menu->id }}, $event.target.value)"
                                    class="w-10 text-center bg-transparent border-0 text-xs font-bold text-stone-800 p-0 focus:ring-0" />
                                <button 
                                    type="button" 
                                    wire:click="incrementQuantity({{ $item->menu->id }})" 
                                    class="w-6 h-6 rounded bg-amber-700 text-white flex items-center justify-center text-xs font-bold shadow-2xs hover:bg-amber-800">
                                    +
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-stone-400 text-xs">
                            Keranjang Anda kosong.
                        </div>
                    @endforelse
                </div>

                <!-- Input Nama Pelanggan & Checkout -->
                @if($this->totalCount > 0)
                    <div class="pt-3 border-t border-stone-200 space-y-3">
                        <div>
                            <label for="customerName" class="block text-xs font-bold text-stone-700 mb-1">
                                Nama Panggilan Anda <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="customerName"
                                wire:model.defer="customerName" 
                                placeholder="Contoh: Maria / Anton" 
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('customerName') border-red-500 @else border-stone-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-2xs" />
                            @error('customerName')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ringkasan Total -->
                        <div class="bg-stone-50 p-3 rounded-xl border border-stone-200 flex justify-between items-center text-sm">
                            <span class="text-stone-600 font-medium text-xs">Total Pembayaran:</span>
                            <span class="font-extrabold text-stone-900 text-base">
                                Rp{{ number_format($this->totalAmount, 0, ',', '.') }}
                            </span>
                        </div>

                        <p class="text-[11px] text-stone-500 text-center">
                            💳 Pembayaran dilakukan di kasir setelah pesanan selesai.
                        </p>

                        <!-- Tombol Submit -->
                        <button 
                            type="button" 
                            wire:click="submitOrder"
                            wire:loading.attr="disabled"
                            class="w-full py-3 bg-amber-800 hover:bg-amber-900 active:scale-98 text-white font-bold rounded-xl shadow-md transition flex items-center justify-center space-x-2 disabled:opacity-50">
                            <span wire:loading.remove>Kirim Pesanan Sekarang</span>
                            <span wire:loading class="flex items-center space-x-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Memproses...</span>
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
