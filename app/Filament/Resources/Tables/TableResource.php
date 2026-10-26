<?php

namespace App\Filament\Resources\Tables;

use App\Filament\Resources\Tables\Pages\CreateTable;
use App\Filament\Resources\Tables\Pages\EditTable;
use App\Filament\Resources\Tables\Pages\ListTables;
use App\Filament\Resources\Tables\Schemas\TableForm;
use App\Filament\Resources\Tables\Tables\TablesTable;
use App\Models\Table as TableModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static ?string $navigationLabel = 'Meja';

    protected static ?string $modelLabel = 'Meja';

    protected static ?string $pluralModelLabel = 'Daftar Meja';

    public static function form(Schema $schema): Schema
    {
        return TableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TablesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTables::route('/'),
            'create' => CreateTable::route('/create'),
            'edit' => EditTable::route('/{record}/edit'),
        ];
    }
}
