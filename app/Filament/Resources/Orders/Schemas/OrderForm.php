<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->label('Nomor Pesanan')
                    ->default(fn () => 'ORD-' . date('Ymd') . '-' . rand(1000, 9999))
                    ->disabled(fn ($context) => $context === 'edit')
                    ->required(),
                Select::make('table_id')
                    ->label('Meja')
                    ->relationship('table', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status Pesanan')
                    ->options(Order::getStatuses())
                    ->default(Order::STATUS_BARU)
                    ->required(),
                TextInput::make('total_amount')
                    ->label('Total Pembayaran (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Repeater::make('orderItems')
                    ->label('Rincian Item')
                    ->relationship('orderItems')
                    ->schema([
                        Select::make('menu_id')
                            ->label('Menu')
                            ->relationship('menu', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        TextInput::make('unit_price')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}
