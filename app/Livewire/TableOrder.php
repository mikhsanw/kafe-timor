<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableOrder extends Component
{
    public Table $table;
    public string $customerName = '';
    public array $cart = []; // [menu_id => quantity]
    public bool $isCartOpen = false;
    public string $search = '';
    public ?int $selectedCategory = null;

    public function mount(string $kode): void
    {
        $this->table = Table::where('code', $kode)->firstOrFail();
    }

    public function selectCategory(?int $categoryId = null): void
    {
        $this->selectedCategory = $categoryId;
    }

    public function addToCart(int $menuId): void
    {
        $menu = Menu::where('id', $menuId)->where('is_available', true)->first();
        if (!$menu) {
            return;
        }

        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]++;
        } else {
            $this->cart[$menuId] = 1;
        }
    }

    public function incrementQuantity(int $menuId): void
    {
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]++;
        }
    }

    public function decrementQuantity(int $menuId): void
    {
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]--;
            if ($this->cart[$menuId] <= 0) {
                unset($this->cart[$menuId]);
            }
        }
    }

    public function updateQuantity(int $menuId, mixed $quantity): void
    {
        $qty = (int) $quantity;
        if ($qty <= 0) {
            unset($this->cart[$menuId]);
        } else {
            $this->cart[$menuId] = min($qty, 99);
        }
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->isCartOpen = false;
    }

    public function toggleCartModal(): void
    {
        $this->isCartOpen = !$this->isCartOpen;
    }

    public function getCartItemsProperty()
    {
        if (empty($this->cart)) {
            return collect();
        }

        $menus = Menu::whereIn('id', array_keys($this->cart))->get()->keyBy('id');

        return collect($this->cart)->map(function ($qty, $menuId) use ($menus) {
            $menu = $menus->get($menuId);
            if (!$menu) {
                return null;
            }

            return (object) [
                'menu' => $menu,
                'quantity' => $qty,
                'subtotal' => $menu->price * $qty,
                'formatted_subtotal' => 'Rp' . number_format($menu->price * $qty, 0, ',', '.'),
            ];
        })->filter();
    }

    public function getTotalCountProperty(): int
    {
        return array_sum($this->cart);
    }

    public function getTotalAmountProperty(): int
    {
        return $this->cartItems->sum('subtotal');
    }

    public function submitOrder()
    {
        $this->validate([
            'customerName' => 'required|string|min:2|max:50',
        ], [
            'customerName.required' => 'Silakan isi nama panggilan Anda.',
            'customerName.min' => 'Nama panggilan minimal 2 karakter.',
        ]);

        if (empty($this->cart)) {
            $this->addError('cart', 'Keranjang pesanan Anda masih kosong.');
            return;
        }

        // Server-Side Verification: Rekalkulasi total dan harga menu langsung dari database
        try {
            $order = DB::transaction(function () {
                $menuIds = array_keys($this->cart);
                $menus = Menu::whereIn('id', $menuIds)
                    ->where('is_available', true)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $serverCalculatedTotal = 0;
                $itemsToCreate = [];

                foreach ($this->cart as $menuId => $qty) {
                    $qty = (int) $qty;
                    if ($qty <= 0) {
                        continue;
                    }

                    $menu = $menus->get($menuId);
                    if (!$menu) {
                        throw new \Exception("Salah satu menu yang dipilih tidak lagi tersedia.");
                    }

                    $subtotal = $menu->price * $qty;
                    $serverCalculatedTotal += $subtotal;

                    $itemsToCreate[] = [
                        'menu_id' => $menu->id,
                        'quantity' => $qty,
                        'unit_price' => $menu->price,
                        'subtotal' => $subtotal,
                    ];
                }

                if (empty($itemsToCreate)) {
                    throw new \Exception('Keranjang pesanan tidak valid.');
                }

                // Generate Order Number unik hari ini
                $dateStr = Carbon::today()->format('Ymd');
                $countToday = Order::whereDate('created_at', Carbon::today())->count() + 1;
                $orderNumber = sprintf('ORD-%s-%04d', $dateStr, $countToday);

                while (Order::where('order_number', $orderNumber)->exists()) {
                    $countToday++;
                    $orderNumber = sprintf('ORD-%s-%04d', $dateStr, $countToday);
                }

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'table_id' => $this->table->id,
                    'customer_name' => trim($this->customerName),
                    'total_amount' => $serverCalculatedTotal,
                    'status' => Order::STATUS_BARU,
                ]);

                foreach ($itemsToCreate as $itemData) {
                    $order->orderItems()->create($itemData);
                }

                return $order;
            });
        } catch (\Exception $e) {
            $this->addError('cart', $e->getMessage());
            return;
        }

        return redirect()->route('order.status', ['nomor' => $order->order_number]);
    }

    public function render()
    {
        $search = trim($this->search);
        $selectedCat = $this->selectedCategory;

        $categoriesQuery = Category::query();

        if ($selectedCat) {
            $categoriesQuery->where('id', $selectedCat);
        }

        $categories = $categoriesQuery->with(['menus' => function ($query) use ($search) {
            $query->where('is_available', true);
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            $query->orderBy('name');
        }])->orderBy('id')->get();

        $allCategories = Category::orderBy('id')->get();

        return view('livewire.table-order', [
            'categories' => $categories,
            'allCategories' => $allCategories,
        ])->layout('components.layouts.app', [
            'table' => $this->table,
            'title' => 'Menu ' . $this->table->name . ' - Kedai Kopi Timor',
        ]);
    }
}
