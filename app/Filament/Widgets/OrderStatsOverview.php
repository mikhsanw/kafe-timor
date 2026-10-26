<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();

        $totalOrdersToday = Order::query()
            ->whereDate('created_at', $today)
            ->count();

        $newOrdersCount = Order::query()
            ->where('status', Order::STATUS_BARU)
            ->count();

        $totalSalesToday = Order::query()
            ->whereDate('created_at', $today)
            ->where('status', Order::STATUS_SELESAI)
            ->sum('total_amount');

        return [
            Stat::make('Pesanan Hari Ini', $totalOrdersToday)
                ->description('Total pesanan masuk hari ini')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('info'),

            Stat::make('Pesanan Baru', $newOrdersCount)
                ->description('Menunggu untuk diproses')
                ->descriptionIcon('heroicon-o-bell-alert')
                ->color($newOrdersCount > 0 ? 'warning' : 'gray'),

            Stat::make('Penjualan Hari Ini', 'Rp' . number_format($totalSalesToday, 0, ',', '.'))
                ->description('Akumulasi pesanan berstatus selesai')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
