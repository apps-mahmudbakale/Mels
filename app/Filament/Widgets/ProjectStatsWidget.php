<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProjectStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProjects = Project::count();
        $completedProjects = Project::where('status', 'completed')->count();
        $inProgressProjects = Project::where('status', 'in_progress')->count();
        $delayedProjects = Project::where('expected_completion_date', '<', now())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'abandoned')
            ->where('completion_percentage', '<', 100)
            ->count();

        $completionRate = $totalProjects > 0 
            ? round(($completedProjects / $totalProjects) * 100, 1) 
            : 0;

        return [
            Stat::make('Total Projects', $totalProjects)
                ->description('All tracked projects')
                ->descriptionIcon('heroicon-o-document-chart-bar')
                ->color('primary'),

            Stat::make('Completed', $completedProjects)
                ->description("$completionRate% completion rate")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('In Progress', $inProgressProjects)
                ->description('Projects currently in progress')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('warning'),

            Stat::make('Delayed', $delayedProjects)
                ->description('Projects behind schedule')
                ->descriptionIcon('heroicon-o-clock')
                ->color('danger'),
        ];
    }

    public static function canView(): bool
    {
        return true;
    }
}
