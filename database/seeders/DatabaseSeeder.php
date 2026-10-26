<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::query()->firstOrCreate(
            ['email' => 'admin@kedai.test'],
            [
                'name' => 'Admin Kedai',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Kategori Menu
        $kopi = Category::query()->firstOrCreate(['name' => 'Kopi']);
        $nonKopi = Category::query()->firstOrCreate(['name' => 'Non-Kopi']);
        $makanan = Category::query()->firstOrCreate(['name' => 'Makanan']);

        // 3. Menu Makanan & Minuman Khas Timor
        $menusData = [
            // Kategori Kopi
            [
                'category_id' => $kopi->id,
                'name' => 'Kopi Flores Bajawa',
                'description' => 'Kopi arabika single origin khas Bajawa dengan aroma floral dan rasa kacang gurih alami.',
                'price' => 22000,
                'is_available' => true,
            ],
            [
                'category_id' => $kopi->id,
                'name' => 'Kopi Susu Gula Aren',
                'description' => 'Espresso berpadu susu segar dan manis legit gula aren murni Timor.',
                'price' => 20000,
                'is_available' => true,
            ],
            [
                'category_id' => $kopi->id,
                'name' => 'Kopi Tubruk Timor',
                'description' => 'Kopi robusta tradisional diseduh pekat dan mantap.',
                'price' => 15000,
                'is_available' => true,
            ],
            [
                'category_id' => $kopi->id,
                'name' => 'Espresso Timor',
                'description' => 'Ekstraksi murni biji kopi arabika Flores pilihan berkrim tebal.',
                'price' => 18000,
                'is_available' => true,
            ],

            // Kategori Non-Kopi
            [
                'category_id' => $nonKopi->id,
                'name' => 'Es Teh Serai',
                'description' => 'Teh segar beraroma serai wangi dengan sedikit sentuhan perasan lemon.',
                'price' => 14000,
                'is_available' => true,
            ],
            [
                'category_id' => $nonKopi->id,
                'name' => 'Teh Tarik Timor',
                'description' => 'Teh hitam pekat berpadu kental manis yang ditarik hingga berbusa lembut.',
                'price' => 18000,
                'is_available' => true,
            ],
            [
                'category_id' => $nonKopi->id,
                'name' => 'Cokelat Panas Gaura',
                'description' => 'Minuman cokelat hangat dari biji kakao asli kepulauan Sumba.',
                'price' => 22000,
                'is_available' => true,
            ],
            [
                'category_id' => $nonKopi->id,
                'name' => 'Air Mineral',
                'description' => 'Air mineral kemasan botol 600ml sejuk dan menyegarkan.',
                'price' => 6000,
                'is_available' => true,
            ],

            // Kategori Makanan
            [
                'category_id' => $makanan->id,
                'name' => "Se'i Sapi Sambal Lu'at",
                'description' => "Daging sapi asap khas Kupang beraroma kayu kosambi disajikan dengan sambal lu'at pedas asam segar.",
                'price' => 38000,
                'is_available' => true,
            ],
            [
                'category_id' => $makanan->id,
                'name' => 'Jagung Bose',
                'description' => 'Bubur jagung manis tradisional Timor dimasak dengan santan gurih dan kacang merah.',
                'price' => 20000,
                'is_available' => true,
            ],
            [
                'category_id' => $makanan->id,
                'name' => 'Pisang Goreng Keju',
                'description' => 'Pisang kepok goreng renyah bertabur keju cheddar parut dan susu manis.',
                'price' => 18000,
                'is_available' => true,
            ],
            [
                'category_id' => $makanan->id,
                'name' => 'Singkong Goreng Gurih',
                'description' => 'Singkong bumbu bawang empuk renyah dengan cocolan sambal terasi.',
                'price' => 15000,
                'is_available' => false, // Menu habis untuk menguji filter ketersediaan
            ],
        ];

        $createdMenus = [];
        foreach ($menusData as $m) {
            $createdMenus[] = Menu::query()->updateOrCreate(
                ['name' => $m['name']],
                $m
            );
        }

        // 4. Meja 1 s.d. Meja 5
        $tables = [];
        for ($i = 1; $i <= 5; $i++) {
            $tables[] = Table::query()->updateOrCreate(
                ['code' => "meja-{$i}"],
                [
                    'name' => "Meja {$i}",
                    'code' => "meja-{$i}",
                ]
            );
        }

        // 5. 10 Pesanan Contoh Hari Ini
        $sampleOrders = [
            [
                'customer' => 'Maria',
                'status' => Order::STATUS_SELESAI,
                'table_idx' => 0,
                'items' => [
                    ['menu_idx' => 0, 'qty' => 1], // Kopi Flores Bajawa
                    ['menu_idx' => 8, 'qty' => 1], // Se'i Sapi
                ],
                'minutes_ago' => 180,
            ],
            [
                'customer' => 'Yohanes',
                'status' => Order::STATUS_SELESAI,
                'table_idx' => 1,
                'items' => [
                    ['menu_idx' => 1, 'qty' => 2], // Kopi Susu Gula Aren
                    ['menu_idx' => 10, 'qty' => 1], // Pisang Goreng Keju
                ],
                'minutes_ago' => 150,
            ],
            [
                'customer' => 'Budi',
                'status' => Order::STATUS_SELESAI,
                'table_idx' => 2,
                'items' => [
                    ['menu_idx' => 9, 'qty' => 2], // Jagung Bose
                    ['menu_idx' => 4, 'qty' => 2], // Es Teh Serai
                ],
                'minutes_ago' => 130,
            ],
            [
                'customer' => 'Dewi',
                'status' => Order::STATUS_SELESAI,
                'table_idx' => 3,
                'items' => [
                    ['menu_idx' => 6, 'qty' => 1], // Cokelat Panas Gaura
                    ['menu_idx' => 10, 'qty' => 1], // Pisang Goreng Keju
                ],
                'minutes_ago' => 100,
            ],
            [
                'customer' => 'Carlos',
                'status' => Order::STATUS_SIAP,
                'table_idx' => 4,
                'items' => [
                    ['menu_idx' => 8, 'qty' => 2], // Se'i Sapi
                    ['menu_idx' => 4, 'qty' => 2], // Es Teh Serai
                ],
                'minutes_ago' => 70,
            ],
            [
                'customer' => 'Eusabio',
                'status' => Order::STATUS_SIAP,
                'table_idx' => 0,
                'items' => [
                    ['menu_idx' => 0, 'qty' => 1], // Kopi Flores Bajawa
                    ['menu_idx' => 9, 'qty' => 1], // Jagung Bose
                ],
                'minutes_ago' => 50,
            ],
            [
                'customer' => 'Anton',
                'status' => Order::STATUS_DIPROSES,
                'table_idx' => 1,
                'items' => [
                    ['menu_idx' => 2, 'qty' => 1], // Kopi Tubruk
                    ['menu_idx' => 10, 'qty' => 1], // Pisang Goreng Keju
                ],
                'minutes_ago' => 35,
            ],
            [
                'customer' => 'Siti',
                'status' => Order::STATUS_DIPROSES,
                'table_idx' => 2,
                'items' => [
                    ['menu_idx' => 5, 'qty' => 1], // Teh Tarik Timor
                    ['menu_idx' => 8, 'qty' => 1], // Se'i Sapi
                ],
                'minutes_ago' => 20,
            ],
            [
                'customer' => 'Roni',
                'status' => Order::STATUS_BARU,
                'table_idx' => 3,
                'items' => [
                    ['menu_idx' => 1, 'qty' => 2], // Kopi Susu Gula Aren
                    ['menu_idx' => 7, 'qty' => 2], // Air Mineral
                ],
                'minutes_ago' => 10,
            ],
            [
                'customer' => 'Fransiska',
                'status' => Order::STATUS_BARU,
                'table_idx' => 4,
                'items' => [
                    ['menu_idx' => 0, 'qty' => 2], // Kopi Flores Bajawa
                    ['menu_idx' => 8, 'qty' => 1], // Se'i Sapi
                    ['menu_idx' => 10, 'qty' => 1], // Pisang Goreng Keju
                ],
                'minutes_ago' => 3,
            ],
        ];

        $today = Carbon::today();
        foreach ($sampleOrders as $idx => $s) {
            $orderNum = sprintf('ORD-%s-%04d', $today->format('Ymd'), $idx + 1);
            $createdAt = Carbon::now()->subMinutes($s['minutes_ago']);

            // Calculate total
            $total = 0;
            $itemsData = [];
            foreach ($s['items'] as $item) {
                $menu = $createdMenus[$item['menu_idx']];
                $subtotal = $menu->price * $item['qty'];
                $total += $subtotal;
                $itemsData[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['qty'],
                    'unit_price' => $menu->price,
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::query()->firstOrCreate(
                ['order_number' => $orderNum],
                [
                    'table_id' => $tables[$s['table_idx']]->id,
                    'customer_name' => $s['customer'],
                    'total_amount' => $total,
                    'status' => $s['status'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );

            // Re-sync items if needed
            if ($order->orderItems()->count() === 0) {
                foreach ($itemsData as $idat) {
                    $order->orderItems()->create([
                        'menu_id' => $idat['menu_id'],
                        'quantity' => $idat['quantity'],
                        'unit_price' => $idat['unit_price'],
                        'subtotal' => $idat['subtotal'],
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }
    }
}
