<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderStatus extends Component
{
    public string $orderNumber;

    public function mount(string $nomor): void
    {
        $this->orderNumber = $nomor;
        // 404 jika nomor pesanan tidak terdaftar
        Order::where('order_number', $nomor)->firstOrFail();
    }

    public function getOrderProperty(): Order
    {
        return Order::with(['table', 'orderItems.menu'])
            ->where('order_number', $this->orderNumber)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.order-status', [
            'order' => $this->order,
        ])->layout('components.layouts.app', [
            'title' => 'Status Pesanan ' . $this->orderNumber . ' - Kedai Kopi Timor',
        ]);
    }
}
