<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Produk Parfum';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Parfum')->required(),
            Forms\Components\TextInput::make('brand')
                ->label('Brand')->nullable(),
            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')->nullable(),
            Forms\Components\TextInput::make('price')
                ->label('Harga')->numeric()->prefix('Rp')->required(),
            Forms\Components\TextInput::make('stock')
                ->label('Stok')->numeric()->required(),
            Forms\Components\Select::make('category')
                ->label('Kategori')
                ->options([
                    'Pria' => 'Pria',
                    'Wanita' => 'Wanita',
                    'Unisex' => 'Unisex',
                ]),
            Forms\Components\TextInput::make('image')
                ->label('URL Gambar')->nullable(),
            Forms\Components\TextInput::make('rating')
                ->label('Rating')->numeric()->nullable(),
            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')->default(true),
            Forms\Components\Toggle::make('is_new')
                ->label('Produk Baru')->default(false),
            Forms\Components\Toggle::make('is_recommended')
                ->label('Rekomendasi')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('image')
                ->label('Foto')
                ->url(fn ($record) => $record->image)
                ->size(60),
            Tables\Columns\TextColumn::make('name')
                ->label('Nama Parfum')->searchable(),
            Tables\Columns\TextColumn::make('category')
                ->label('Kategori'),
            Tables\Columns\TextColumn::make('price')
                ->label('Harga')->money('IDR'),
            Tables\Columns\TextColumn::make('stock')
                ->label('Stok'),
            Tables\Columns\TextColumn::make('rating')
                ->label('Rating'),
            Tables\Columns\IconColumn::make('is_active')
                ->label('Aktif')->boolean(),
            Tables\Columns\IconColumn::make('is_recommended')
                ->label('Rekomendasi')->boolean(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}