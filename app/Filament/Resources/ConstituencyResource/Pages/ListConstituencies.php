<?php

namespace App\Filament\Resources\ConstituencyResource\Pages;

use App\Filament\Resources\ConstituencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConstituencies extends ListRecords
{
    protected static string $resource = ConstituencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
