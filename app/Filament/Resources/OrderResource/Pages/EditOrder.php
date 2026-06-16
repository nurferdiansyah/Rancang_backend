<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Notification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterSave(): void
    {
        Notification::create([
            'user_id' => $this->record->user_id,
            'title' => 'Status Pesanan Diperbarui',
            'message' => 'Pesanan #' . $this->record->id . ' sekarang berstatus ' . $this->record->status,
            'type' => 'order',
            'is_read' => 0,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}