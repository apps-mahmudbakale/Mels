<?php

namespace App\Filament\Resources\ConstituencyResource\Pages;

use App\Filament\Resources\ConstituencyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateConstituency extends CreateRecord
{
    protected static string $resource = ConstituencyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
