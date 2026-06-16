<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Produk', Product::count())
                ->description('Semua produk parfum')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Total Pesanan', Order::count())
                ->description('Semua pesanan masuk')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Pesanan Pending', Order::where('status', 'pending')->count())
                ->description('Menunggu diproses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pesanan Selesai', Order::where('status', 'selesai')->count())
                ->description('Berhasil dikirim')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format(Order::where('status', 'selesai')->sum('total_amount'), 0, ',', '.'))
                ->description('Dari pesanan selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Total Stok', Product::sum('stock'))
                ->description('Stok semua produk')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('primary'),
        ];
    }
}