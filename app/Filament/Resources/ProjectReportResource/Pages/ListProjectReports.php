<?php

namespace App\Filament\Resources\ProjectReportResource\Pages;

use App\Filament\Resources\ProjectReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProjectReports extends ListRecords
{
    protected static string $resource = ProjectReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate_report')
                ->label('Generate Report')
                ->icon('heroicon-o-chart-bar')
                ->url(static::getUrl(['index'])),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProjectReportResource\Widgets\ProjectStatusOverview::class,
            ProjectReportResource\Widgets\ProjectsByStatusChart::class,
            ProjectReportResource\Widgets\ProjectsByLGAChart::class,
            ProjectReportResource\Widgets\ProjectsByAspirantChart::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Projects'),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed')),
            'in_progress' => Tab::make('In Progress')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_progress')),
            'on_hold' => Tab::make('On Hold')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'on_hold')),
            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled')),
        ];
    }
}
