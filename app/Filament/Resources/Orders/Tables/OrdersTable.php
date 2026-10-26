<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin!'),
                TextColumn::make('table.name')
                    ->label('Meja')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('items_summary')
                    ->label('Item Pesanan')
                    ->state(function (Order $record) {
                        return $record->orderItems->map(function ($item) {
                            $name = $item->menu?->name ?? 'Menu Dihapus';
                            return "{$item->quantity}x {$name}";
                        })->implode(', ');
                    })
                    ->wrap()
                    ->limit(60),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Order::STATUS_BARU => 'warning',
                        Order::STATUS_DIPROSES => 'info',
                        Order::STATUS_SIAP => 'primary',
                        Order::STATUS_SELESAI => 'success',
                        Order::STATUS_DIBATALKAN => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Order::getStatuses()[$state] ?? ucfirst($state))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('H:i, d M')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options(Order::getStatuses()),
                SelectFilter::make('table_id')
                    ->label('Meja')
                    ->relationship('table', 'name'),
            ])
            ->recordActions([
                // Quick transition buttons based on current state
                Action::make('process')
                    ->label('Proses')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (Order $record) => $record->status === Order::STATUS_BARU)
                    ->requiresConfirmation()
                    ->modalHeading('Mulai Proses Pesanan')
                    ->modalDescription('Ubah status pesanan ini menjadi "Diproses"?')
                    ->modalSubmitActionLabel('Ya, Proses')
                    ->action(function (Order $record) {
                        $record->update(['status' => Order::STATUS_DIPROSES]);
                        Notification::make()
                            ->title('Status Diperbarui')
                            ->body("Pesanan {$record->order_number} sedang diproses.")
                            ->success()
                            ->send();
                    }),

                Action::make('ready')
                    ->label('Siap')
                    ->icon('heroicon-o-check')
                    ->color('primary')
                    ->visible(fn (Order $record) => $record->status === Order::STATUS_DIPROSES)
                    ->requiresConfirmation()
                    ->modalHeading('Pesanan Siap')
                    ->modalDescription('Ubah status pesanan ini menjadi "Siap Disajikan"?')
                    ->modalSubmitActionLabel('Ya, Siap')
                    ->action(function (Order $record) {
                        $record->update(['status' => Order::STATUS_SIAP]);
                        Notification::make()
                            ->title('Status Diperbarui')
                            ->body("Pesanan {$record->order_number} siap disajikan.")
                            ->success()
                            ->send();
                    }),

                Action::make('complete')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === Order::STATUS_SIAP)
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Pesanan')
                    ->modalDescription('Tandai pesanan ini sebagai "Selesai"?')
                    ->modalSubmitActionLabel('Ya, Selesai')
                    ->action(function (Order $record) {
                        $record->update(['status' => Order::STATUS_SELESAI]);
                        Notification::make()
                            ->title('Status Diperbarui')
                            ->body("Pesanan {$record->order_number} telah selesai.")
                            ->success()
                            ->send();
                    }),

                ActionGroup::make([
                    Action::make('change_status')
                        ->label('Ubah Status Manual')
                        ->icon('heroicon-o-arrows-up-down')
                        ->color('gray')
                        ->form([
                            Select::make('status')
                                ->label('Pilih Status')
                                ->options(Order::getStatuses())
                                ->required(),
                        ])
                        ->fillForm(fn (Order $record): array => [
                            'status' => $record->status,
                        ])
                        ->action(function (Order $record, array $data): void {
                            $record->update(['status' => $data['status']]);
                            Notification::make()
                                ->title('Status Berhasil Diubah')
                                ->body("Status pesanan diubah ke " . Order::getStatuses()[$data['status']])
                                ->success()
                                ->send();
                        }),

                    Action::make('view_customer_page')
                        ->label('Buka Status Pelanggan')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (Order $record) => url('/pesanan/' . $record->order_number))
                        ->openUrlInNewTab(),

                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
