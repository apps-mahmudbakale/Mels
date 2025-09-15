<?php

namespace App\Filament\Resources\AspirantResource\Pages;

use App\Filament\Resources\AspirantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAspirant extends ViewRecord
{
    protected static string $resource = AspirantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
