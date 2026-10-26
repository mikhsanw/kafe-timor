<?php

namespace Tests\Feature;

use App\Livewire\OrderStatus;
use App\Livewire\TableOrder;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class KedaiKopiTimorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Kriteria 1: php artisan migrate --seed berjalan tanpa error pada database kosong
     */
    public function test_seeder_runs_successfully(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@kedai.test']);
        $this->assertDatabaseCount('categories', 3);
        $this->assertDatabaseCount('tables', 5);
        $this->assertEquals(10, Order::count());
    }

    /**
     * Kriteria 2 & Kriteria 6: Kode meja tidak valid menampilkan 404
     */
    public function test_invalid_table_code_returns_404(): void
    {
        $response = $this->get('/meja/kode-tidak-ada-xyz');
        $response->assertStatus(404);
    }

    /**
     * Kriteria 3: Membuka /meja/{kode} menampilkan nama meja dan menu yang tersedia saja
     */
    public function test_table_page_displays_only_available_menus(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/meja/meja-1');
        $response->assertStatus(200);
        $response->assertSee('Meja 1');
        $response->assertSee('Kopi Flores Bajawa');
        $response->assertSee("Se'i Sapi Sambal Lu'at");
        // Singkong Goreng Gurih diset is_available = false di seeder, jadi tidak boleh muncul
        $response->assertDontSee('Singkong Goreng Gurih');
    }

    /**
     * Kriteria 4 & 7: Pelanggan memesan ≥ 2 item berbeda, total dihitung di server
     */
    public function test_customer_can_order_multiple_items_and_total_is_calculated_on_server(): void
    {
        $this->seed(DatabaseSeeder::class);

        $table = Table::where('code', 'meja-1')->first();
        $menu1 = Menu::where('name', 'Kopi Flores Bajawa')->first(); // 22.000
        $menu2 = Menu::where('name', "Se'i Sapi Sambal Lu'at")->first(); // 38.000

        $expectedTotal = (22000 * 2) + (38000 * 1); // 44000 + 38000 = 82000

        Livewire::test(TableOrder::class, ['kode' => $table->code])
            ->set('cart', [
                $menu1->id => 2,
                $menu2->id => 1,
            ])
            ->set('customerName', 'Maria')
            ->call('submitOrder')
            ->assertHasNoErrors()
            ->assertRedirect();

        // Verifikasi pesanan tersimpan di database dengan total yang tepat
        $order = Order::where('customer_name', 'Maria')->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertEquals($table->id, $order->table_id);
        $this->assertEquals($expectedTotal, $order->total_amount);
        $this->assertEquals(Order::STATUS_BARU, $order->status);

        // Verifikasi item pesanan
        $this->assertCount(2, $order->orderItems);
    }

    /**
     * Pelanggan tidak bisa memesan jika nama kosong
     */
    public function test_order_submission_requires_customer_name(): void
    {
        $this->seed(DatabaseSeeder::class);
        $table = Table::where('code', 'meja-1')->first();
        $menu = Menu::first();

        Livewire::test(TableOrder::class, ['kode' => $table->code])
            ->set('cart', [$menu->id => 1])
            ->set('customerName', '')
            ->call('submitOrder')
            ->assertHasErrors(['customerName']);
    }

    /**
     * Total dihitung ulang di server dan menolak menu yang habis/tidak tersedia
     */
    public function test_server_rejects_unavailable_menu_in_cart(): void
    {
        $this->seed(DatabaseSeeder::class);
        $table = Table::where('code', 'meja-1')->first();
        $unavailableMenu = Menu::where('is_available', false)->first();

        Livewire::test(TableOrder::class, ['kode' => $table->code])
            ->set('cart', [$unavailableMenu->id => 1])
            ->set('customerName', 'Budi')
            ->call('submitOrder')
            ->assertHasErrors(['cart']);
    }

    /**
     * Kriteria 5: Halaman status pesanan menampilkan detail dan dapat diakses
     */
    public function test_order_status_page_displays_correct_information(): void
    {
        $this->seed(DatabaseSeeder::class);

        $order = Order::first();

        $response = $this->get('/pesanan/' . $order->order_number);
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee($order->customer_name);
        $response->assertSee($order->table->name);
        $response->assertSee('wire:poll.5s', false);
    }

    /**
     * Perubahan status oleh admin langsung tercermin di order status Livewire
     */
    public function test_order_status_reflects_updated_status_via_polling(): void
    {
        $this->seed(DatabaseSeeder::class);
        $order = Order::where('status', Order::STATUS_BARU)->first();

        $test = Livewire::test(OrderStatus::class, ['nomor' => $order->order_number])
            ->assertSee('Pesanan Diterima');

        // Admin update status to diproses
        $order->update(['status' => Order::STATUS_DIPROSES]);

        // Livewire re-render / poll
        $test->call('$refresh')
            ->assertSee('Sedang Diracik / Dimasak');

        // Admin update status to siap
        $order->update(['status' => Order::STATUS_SIAP]);

        $test->call('$refresh')
            ->assertSee('Pesanan Siap!');

        // Admin update status to selesai
        $order->update(['status' => Order::STATUS_SELESAI]);

        $test->call('$refresh')
            ->assertSee('Pesanan Selesai');
    }

    /**
     * Nomor pesanan yang tidak valid menghasilkan 404
     */
    public function test_invalid_order_number_returns_404(): void
    {
        $response = $this->get('/pesanan/ORD-INVALID-9999');
        $response->assertStatus(404);
    }

    /**
     * Verifikasi Admin Filament Login
     */
    public function test_admin_can_access_filament(): void
    {
        $this->seed(DatabaseSeeder::class);
        $admin = User::where('email', 'admin@kedai.test')->first();

        $this->actingAs($admin);

        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Kedai Kopi Timor');
    }
}
