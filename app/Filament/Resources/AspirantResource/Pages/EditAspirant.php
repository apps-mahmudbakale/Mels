<?php

namespace App\Filament\Resources\AspirantResource\Pages;

use App\Filament\Resources\AspirantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAspirant extends EditRecord
{
    protected static string $resource = AspirantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
