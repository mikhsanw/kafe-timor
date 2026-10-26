<div class="space-y-6 sm:space-y-8">
    <!-- Hero Banner (Fullscreen Width & Warm Atmospheric Aesthetic) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-stone-900 via-amber-950 to-stone-900 text-white p-6 sm:p-8 lg:p-10 shadow-xl border border-amber-900/40">
        <!-- Background Ambient Glow & Patterns -->
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-64 h-64 bg-amber-700/15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 backdrop-blur-md shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                    <span>📍 Anda di {{ $table->name }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight text-white">
                    Pesan Langsung dari Meja
                </h1>
                <p class="text-xs sm:text-sm text-stone-300/90 leading-relaxed">
                    Nikmati racikan kopi autentik Flores Bajawa dan aneka kuliner khas Timor tanpa perlu mengantre ke kasir.
                </p>
            </div>

            <!-- Search Bar Inside Hero -->
            <div class="w-full md:w-80 shrink-0">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.250ms="search" 
                        placeholder="Cari kopi, se'i, bose..." 
                        class="w-full pl-10 pr-4 py-3 bg-white/10 hover:bg-white/15 focus:bg-white text-white focus:text-stone-900 placeholder:text-stone-400 rounded-2xl border border-white/20 focus:border-amber-400 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/30 backdrop-blur-md transition-all duration-200" />
                    <div class="absolute left-3.5 top-3.5 text-stone-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    @if(!empty($search))
                        <button 
                            type="button" 
                            wire:click="$set('search', '')" 
                            class="absolute right-3 top-3 text-stone-400 hover:text-white bg-stone-800/60 rounded-full w-5 h-5 flex items-center justify-center text-xs">
                            ✕
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Category Filter Bar (Sticky with Glassmorphism) -->
    <div class="sticky top-16 z-30 -mx-4 sm:mx-0 px-4 sm:px-0 py-3 bg-stone-100/90 backdrop-blur-xl border-y sm:border-y-0 sm:border-b border-stone-200/80 overflow-x-auto no-scrollbar flex items-center space-x-2">
        <button 
            type="button"
            wire:click="selectCategory(null)"
            class="shrink-0 px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center space-x-1.5 shadow-2xs {{ is_null($selectedCategory) ? 'bg-amber-800 text-white shadow-md shadow-amber-900/20 scale-102' : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200' }}">
            <span>Semua Menu</span>
        </button>

        @foreach($allCategories as $cat)
            @php
                $catIcon = match(strtolower($cat->name)) {
                    'kopi' => '☕',
                    'non-kopi' => '🧋',
                    'makanan' => '🍲',
                    default => '🍽️',
                };
            @endphp
            <button 
                type="button"
                wire:click="selectCategory({{ $cat->id }})"
                class="shrink-0 px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center space-x-1.5 shadow-2xs {{ $selectedCategory === $cat->id ? 'bg-amber-800 text-white shadow-md shadow-amber-900/20 scale-102' : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200' }}">
                <span>{{ $catIcon }}</span>
                <span>{{ $cat->name }}</span>
            </button>
        @endforeach
    </div>

    <!-- Error Banner jika ada -->
    @error('cart')
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-xs sm:text-sm font-semibold flex items-center space-x-2 animate-fade-scale">
            <span>⚠️</span>
            <span>{{ $message }}</span>
        </div>
    @enderror

    <!-- Fullscreen Fluid Layout (Grid Menu + Sidebar Cart on Large Screens) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Menu List (Expands to 8 cols on desktop or 12 if cart empty) -->
        <div class="{{ $this->totalCount > 0 ? 'lg:col-span-8' : 'lg:col-span-12' }} space-y-8 transition-all duration-300">
            @forelse($categories as $category)
                @if($category->menus->isNotEmpty())
                    <section class="space-y-4">
                        <div class="flex items-center justify-between border-b border-stone-200 pb-2">
                            <h2 class="text-base sm:text-lg font-bold text-stone-900 flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                                <span>{{ $category->name }}</span>
                            </h2>
                            <span class="text-xs font-semibold text-stone-500 bg-stone-200/60 px-2.5 py-0.5 rounded-full">
                                {{ $category->menus->count() }} menu
                            </span>
                        </div>

                        <!-- Menu Cards Grid (1 col mobile, 2 cols tablet/desktop, 3 cols large when cart hidden) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $this->totalCount > 0 ? 'xl:grid-cols-2' : 'xl:grid-cols-3' }} gap-4">
                            @foreach($category->menus as $menu)
                                <div class="group bg-white rounded-3xl p-5 border border-stone-200/90 shadow-2xs hover:shadow-md hover:border-amber-400/80 transition-all duration-200 flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <div class="flex items-start justify-between gap-2">
                                            <h3 class="font-bold text-stone-900 text-sm sm:text-base group-hover:text-amber-900 transition-colors leading-snug">
                                                {{ $menu->name }}
                                            </h3>
                                            <span class="shrink-0 text-xs px-2 py-0.5 rounded-full bg-stone-100 text-stone-600 font-medium">
                                                {{ $category->name }}
                                            </span>
                                        </div>

                                        @if($menu->description)
                                            <p class="text-xs text-stone-500 leading-relaxed line-clamp-2">
                                                {{ $menu->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Bottom Card: Price & Quantity Controls -->
                                    <div class="pt-4 mt-2 border-t border-stone-100 flex items-center justify-between">
                                        <div class="text-base font-extrabold text-amber-950">
                                            {{ $menu->formatted_price }}
                                        </div>

                                        <div>
                                            @if(isset($cart[$menu->id]) && $cart[$menu->id] > 0)
                                                <div class="flex items-center space-x-1.5 bg-amber-50 border border-amber-300 rounded-2xl p-1 shadow-xs animate-fade-scale">
                                                    <button 
                                                        type="button" 
                                                        wire:click="decrementQuantity({{ $menu->id }})" 
                                                        class="w-8 h-8 rounded-xl bg-white text-stone-800 border border-amber-200 flex items-center justify-center font-bold text-sm hover:bg-amber-100 active:scale-90 transition duration-150 shadow-2xs"
                                                        aria-label="Kurangi">
                                                        -
                                                    </button>
                                                    <span class="w-7 text-center text-xs sm:text-sm font-extrabold text-amber-950">
                                                        {{ $cart[$menu->id] }}
                                                    </span>
                                                    <button 
                                                        type="button" 
                                                        wire:click="incrementQuantity({{ $menu->id }})" 
                                                        class="w-8 h-8 rounded-xl bg-amber-800 text-white flex items-center justify-center font-bold text-sm hover:bg-amber-900 active:scale-90 transition duration-150 shadow-xs"
                                                        aria-label="Tambah">
                                                        +
                                                    </button>
                                                </div>
                                            @else
                                                <button 
                                                    type="button" 
                                                    wire:click="addToCart({{ $menu->id }})" 
                                                    class="px-4 py-2 bg-stone-900 hover:bg-amber-800 active:scale-95 text-white text-xs sm:text-sm font-bold rounded-2xl shadow-xs transition duration-200 flex items-center space-x-1.5">
                                                    <span>+ Tambah</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @empty
                <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-stone-300 p-8 space-y-3">
                    <span class="text-4xl">🔍</span>
                    <h3 class="text-base font-bold text-stone-800">Menu tidak ditemukan</h3>
                    <p class="text-xs text-stone-500">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                    <button 
                        type="button" 
                        wire:click="$set('search', '')" 
                        class="px-4 py-2 bg-amber-800 text-white text-xs font-bold rounded-xl shadow-xs hover:bg-amber-900 transition">
                        Reset Pencarian
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Right: Desktop Sticky Order Panel (Visible on lg+ screens when cart has items) -->
        @if($this->totalCount > 0)
            <div class="hidden lg:block lg:col-span-4 sticky top-24">
                <div class="bg-white rounded-3xl border border-stone-200 shadow-xl p-6 space-y-5 animate-fade-scale">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <h3 class="font-extrabold text-stone-900 text-lg">Keranjang Pesanan</h3>
                            <p class="text-xs text-stone-500">{{ $table->name }} • {{ $this->totalCount }} item dipilih</p>
                        </div>
                        <button 
                            type="button" 
                            wire:click="clearCart" 
                            class="text-xs text-red-600 hover:text-red-700 font-semibold hover:underline">
                            Kosongkan
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-3 max-h-80 overflow-y-auto pr-1 divide-y divide-stone-100">
                        @foreach($this->cartItems as $item)
                            <div class="pt-3 first:pt-0 flex items-center justify-between space-x-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-stone-900 text-xs sm:text-sm truncate">{{ $item->menu->name }}</h4>
                                    <div class="text-[11px] text-stone-500">{{ $item->menu->formatted_price }}</div>
                                    <div class="text-xs font-extrabold text-amber-900 mt-0.5">{{ $item->formatted_subtotal }}</div>
                                </div>

                                <div class="flex items-center space-x-1 bg-stone-100 rounded-xl p-0.5">
                                    <button 
                                        type="button" 
                                        wire:click="decrementQuantity({{ $item->menu->id }})" 
                                        class="w-6 h-6 rounded-lg bg-white text-stone-700 flex items-center justify-center text-xs font-bold shadow-2xs hover:bg-stone-50">
                                        -
                                    </button>
                                    <span class="w-8 text-center text-xs font-extrabold text-stone-900">
                                        {{ $item->quantity }}
                                    </span>
                                    <button 
                                        type="button" 
                                        wire:click="incrementQuantity({{ $item->menu->id }})" 
                                        class="w-6 h-6 rounded-lg bg-amber-800 text-white flex items-center justify-center text-xs font-bold shadow-2xs hover:bg-amber-900">
                                        +
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Input Nama Pelanggan -->
                    <div class="pt-3 border-t border-stone-200 space-y-2">
                        <label for="customerNameDesktop" class="block text-xs font-bold text-stone-700">
                            Nama Panggilan Anda <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="customerNameDesktop"
                            wire:model.defer="customerName" 
                            placeholder="Contoh: Maria / Anton" 
                            class="w-full px-4 py-2.5 rounded-2xl border @error('customerName') border-red-500 @else border-stone-300 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-2xs" />
                        @error('customerName')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subtotal & Total Summary -->
                    <div class="bg-amber-50/70 p-4 rounded-2xl border border-amber-200/80 space-y-2">
                        <div class="flex justify-between items-center text-xs text-stone-600">
                            <span>Jumlah Item</span>
                            <span class="font-bold text-stone-800">{{ $this->totalCount }} porsi</span>
                        </div>
                        <div class="flex justify-between items-center text-sm sm:text-base font-extrabold text-stone-900 border-t border-amber-200/80 pt-2">
                            <span>Total Pembayaran:</span>
                            <span class="text-amber-950 text-lg">Rp{{ number_format($this->totalAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-stone-500 text-center">
                        💳 Pembayaran di kasir setelah selesai bersantap.
                    </p>

                    <!-- Submit Button -->
                    <button 
                        type="button" 
                        wire:click="submitOrder"
                        wire:loading.attr="disabled"
                        class="w-full py-3.5 bg-gradient-to-r from-amber-800 via-amber-900 to-amber-950 hover:from-amber-900 hover:to-black active:scale-98 text-white font-extrabold rounded-2xl shadow-lg shadow-amber-900/20 transition-all duration-200 flex items-center justify-center space-x-2 disabled:opacity-50">
                        <span wire:loading.remove class="flex items-center space-x-2">
                            <span>Kirim Pesanan Sekarang</span>
                            <span>→</span>
                        </span>
                        <span wire:loading class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Memproses Pesanan...</span>
                        </span>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Floating Bottom Bar (Untuk Mobile & Tablet) -->
    @if($this->totalCount > 0)
        <div class="lg:hidden fixed bottom-4 left-0 right-0 z-40 px-4 animate-fade-scale">
            <div class="max-w-md mx-auto bg-stone-900/95 text-white p-4 rounded-3xl shadow-2xl flex items-center justify-between border border-stone-800 backdrop-blur-xl animate-cart-bounce">
                <div class="flex items-center space-x-3 cursor-pointer" wire:click="toggleCartModal">
                    <div class="relative w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-600 to-amber-800 flex items-center justify-center text-xl shadow-md">
                        🛍️
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-stone-900 animate-pulse">
                            {{ $this->totalCount }}
                        </span>
                    </div>
                    <div>
                        <div class="text-[11px] text-stone-400 font-medium">{{ $this->totalCount }} item dipilih</div>
                        <div class="text-base font-extrabold text-amber-400 leading-tight">Rp{{ number_format($this->totalAmount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <button 
                    type="button" 
                    wire:click="toggleCartModal"
                    class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 active:scale-95 text-stone-950 text-xs font-extrabold rounded-2xl transition duration-150 shadow-md flex items-center space-x-1.5">
                    <span>Lihat & Pesan</span>
                    <span class="font-bold">→</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Mobile Slide-Up Drawer Modal (Bottom Sheet) -->
    @if($isCartOpen)
        <div class="lg:hidden fixed inset-0 z-50 overflow-y-auto bg-stone-950/70 backdrop-blur-md flex items-end justify-center">
            <div class="w-full max-w-lg bg-white rounded-t-3xl p-6 shadow-2xl max-h-[92vh] flex flex-col animate-drawer-up border-t border-stone-200">
                <!-- Modal Top Handle & Header -->
                <div class="w-12 h-1.5 bg-stone-300 rounded-full mx-auto mb-4"></div>
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                    <div>
                        <h3 class="font-extrabold text-stone-900 text-lg">Keranjang Pesanan</h3>
                        <p class="text-xs text-stone-500">{{ $table->name }} • {{ $this->totalCount }} item dipilih</p>
                    </div>
                    <button 
                        type="button" 
                        wire:click="toggleCartModal"
                        class="w-9 h-9 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center hover:bg-stone-200 transition font-bold text-sm">
                        ✕
                    </button>
                </div>

                <!-- Item Keranjang List -->
                <div class="flex-1 overflow-y-auto py-3 space-y-3 divide-y divide-stone-100">
                    @forelse($this->cartItems as $item)
                        <div class="pt-3 first:pt-0 flex items-center justify-between space-x-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-stone-900 text-xs sm:text-sm truncate">{{ $item->menu->name }}</h4>
                                <div class="text-[11px] text-stone-500">
                                    {{ $item->menu->formatted_price }} / porsi
                                </div>
                                <div class="text-xs font-extrabold text-amber-900 mt-0.5">
                                    {{ $item->formatted_subtotal }}
                                </div>
                            </div>

                            <!-- Quantity Controller -->
                            <div class="flex items-center space-x-1 bg-stone-100 rounded-xl p-1">
                                <button 
                                    type="button" 
                                    wire:click="decrementQuantity({{ $item->menu->id }})" 
                                    class="w-7 h-7 rounded-lg bg-white text-stone-700 flex items-center justify-center text-xs font-bold shadow-2xs hover:bg-stone-50 active:scale-90 transition">
                                    -
                                </button>
                                <input 
                                    type="number" 
                                    min="1" 
                                    max="99" 
                                    value="{{ $item->quantity }}" 
                                    wire:change="updateQuantity({{ $item->menu->id }}, $event.target.value)"
                                    class="w-10 text-center bg-transparent border-0 text-xs font-extrabold text-stone-800 p-0 focus:ring-0" />
                                <button 
                                    type="button" 
                                    wire:click="incrementQuantity({{ $item->menu->id }})" 
                                    class="w-7 h-7 rounded-lg bg-amber-800 text-white flex items-center justify-center text-xs font-bold shadow-2xs hover:bg-amber-900 active:scale-90 transition">
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
                            <label for="customerNameMobile" class="block text-xs font-bold text-stone-700 mb-1">
                                Nama Panggilan Anda <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="customerNameMobile"
                                wire:model.defer="customerName" 
                                placeholder="Contoh: Maria / Anton" 
                                class="w-full px-4 py-2.5 rounded-2xl border @error('customerName') border-red-500 @else border-stone-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-2xs" />
                            @error('customerName')
                                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ringkasan Total -->
                        <div class="bg-amber-50/70 p-3.5 rounded-2xl border border-amber-200 flex justify-between items-center text-sm">
                            <span class="text-stone-600 font-semibold text-xs">Total Pembayaran:</span>
                            <span class="font-black text-amber-950 text-lg">
                                Rp{{ number_format($this->totalAmount, 0, ',', '.') }}
                            </span>
                        </div>

                        <p class="text-[11px] text-stone-500 text-center">
                            💳 Pembayaran di kasir setelah pesanan selesai.
                        </p>

                        <!-- Tombol Submit -->
                        <button 
                            type="button" 
                            wire:click="submitOrder"
                            wire:loading.attr="disabled"
                            class="w-full py-3.5 bg-gradient-to-r from-amber-800 to-amber-950 hover:to-black active:scale-98 text-white font-extrabold rounded-2xl shadow-lg shadow-amber-900/20 transition flex items-center justify-center space-x-2 disabled:opacity-50">
                            <span wire:loading.remove>Kirim Pesanan Sekarang →</span>
                            <span wire:loading class="flex items-center space-x-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Memproses Pesanan...</span>
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
