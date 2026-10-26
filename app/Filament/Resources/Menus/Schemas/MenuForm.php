<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Nama Menu')
                    ->placeholder('Contoh: Kopi Flores Bajawa')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Deskripsi singkat bahan dan cita rasa...')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->label('Harga (Rp)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->placeholder('20000'),
                Toggle::make('is_available')
                    ->label('Tersedia untuk dipesan')
                    ->default(true)
                    ->required(),
            ]);
    }
}
