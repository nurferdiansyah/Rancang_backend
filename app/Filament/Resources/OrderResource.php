<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pesanan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Pesanan')
                ->schema([
                    Forms\Components\TextInput::make('shipping_name')
                        ->label('Nama Pemesan')
                        ->disabled(),
                    Forms\Components\TextInput::make('shipping_phone')
                        ->label('No. Telepon')
                        ->disabled(),
                    Forms\Components\Textarea::make('shipping_address')
                        ->label('Alamat Pengiriman')
                        ->disabled()
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Detail Pembayaran')
                ->schema([
                    Forms\Components\TextInput::make('total_amount')
                        ->label('Total Pembayaran')
                        ->prefix('Rp')
                        ->disabled(),
                    Forms\Components\TextInput::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->disabled(),
                    Forms\Components\Select::make('status')
                        ->label('Status Pesanan')
                        ->options([
                            'pending'   => 'Pending',
                            'diproses'  => 'Diproses',
                            'dikirim'   => 'Dikirim',
                            'selesai'   => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        ])
                        ->required(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID Pesanan')
                ->sortable(),
            Tables\Columns\TextColumn::make('shipping_name')
                ->label('Nama Pemesan')
                ->searchable(),
            Tables\Columns\TextColumn::make('shipping_phone')
                ->label('No. Telepon'),
            Tables\Columns\TextColumn::make('total_amount')
                ->label('Total')
                ->money('IDR')
                ->sortable(),
            Tables\Columns\TextColumn::make('payment_method')
                ->label('Pembayaran'),
            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'warning'  => 'pending',
                    'primary'  => 'diproses',
                    'info'     => 'dikirim',
                    'success'  => 'selesai',
                    'danger'   => 'dibatalkan',
                ]),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime('d M Y, H:i')
                ->sortable(),
        ])
        ->defaultSort('created_at', 'desc')
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending'    => 'Pending',
                    'diproses'   => 'Diproses',
                    'dikirim'    => 'Dikirim',
                    'selesai'    => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->label('Update Status'),
            Tables\Actions\ViewAction::make()
                ->label('Detail'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}