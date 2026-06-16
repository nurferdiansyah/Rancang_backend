<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Customer';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Customer')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama')
                        ->disabled(),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->disabled(),
                    Forms\Components\TextInput::make('created_at')
                        ->label('Bergabung')
                        ->disabled(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->width('60px'),
            Tables\Columns\TextColumn::make('name')
                ->label('Nama')
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->searchable(),
            Tables\Columns\TextColumn::make('orders_count')
                ->label('Total Pesanan')
                ->counts('orders')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Bergabung')
                ->dateTime('d M Y')
                ->sortable(),
        ])
        ->defaultSort('created_at', 'desc')
        ->actions([
            Tables\Actions\ViewAction::make()
                ->label('Detail'),
        ])
        ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}