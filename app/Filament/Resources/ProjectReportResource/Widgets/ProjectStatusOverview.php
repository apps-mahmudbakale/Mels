<?php

namespace App\Filament\Resources\ProjectReportResource\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectStatusOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', Project::count())
                ->description('All projects')
                ->color('primary')
                ->icon('heroicon-o-clipboard-document-check'),
                
            Stat::make('Completed', Project::where('status', 'completed')->count())
                ->description('Successfully delivered')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
                
            Stat::make('In Progress', Project::where('status', 'in_progress')->count())
                ->description('Currently being implemented')
                ->color('warning')
                ->icon('heroicon-o-arrow-path'),
                
            Stat::make('On Hold', Project::where('status', 'on_hold')->count())
                ->description('Temporarily paused')
                ->color('info')
                ->icon('heroicon-o-pause-circle'),
                
            Stat::make('Cancelled', Project::where('status', 'cancelled')->count())
                ->description('Discontinued')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
        ];
    }
}
