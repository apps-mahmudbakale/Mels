<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Illuminate\Database\Eloquent\Model;

class ProjectTimeline extends Entry
{
    protected string $view = 'filament.infolists.components.project-timeline';

    public static function make(string $name): static
    {
        return parent::make($name)
            ->hiddenLabel()
            ->columnSpanFull();
    }

    public function getUpdatesProperty()
    {
        return $this->getRecord()
            ->updates()
            ->with(['user'])
            ->orderBy('update_date', 'desc')
            ->get();
    }
}
