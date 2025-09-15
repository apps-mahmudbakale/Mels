<?php

namespace App\Filament\Resources\AspirantResource\Pages;

use App\Filament\Resources\AspirantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAspirants extends ListRecords
{
    protected static string $resource = AspirantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
