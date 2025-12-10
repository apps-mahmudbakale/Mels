<?php

namespace App\Filament\Resources\ProjectUpdateResource\Pages;

use App\Filament\Resources\ProjectUpdateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectUpdate extends CreateRecord
{
    protected static string $resource = ProjectUpdateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
