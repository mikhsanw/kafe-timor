<?php

namespace App\Filament\Resources\Tables\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Meja')
                    ->placeholder('Contoh: Meja 1')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Kode Unik Meja')
                    ->placeholder('Contoh: meja-1 atau M01')
                    ->helperText('Digunakan pelanggan untuk mengakses menu melalui link /meja/{kode}')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
            ]);
    }
}
