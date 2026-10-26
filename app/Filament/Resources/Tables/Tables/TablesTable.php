<?php

namespace App\Filament\Resources\Tables\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Meja')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('code')
                    ->label('Kode Meja')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('Tautan Pemesanan')
                    ->state(fn ($record) => url('/meja/' . $record->code))
                    ->copyable()
                    ->copyMessage('URL disalin!')
                    ->color('info')
                    ->icon('heroicon-o-link'),
                TextColumn::make('orders_count')
                    ->label('Total Pesanan')
                    ->counts('orders')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('open_menu')
                    ->label('Buka Menu')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn ($record) => url('/meja/' . $record->code))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
