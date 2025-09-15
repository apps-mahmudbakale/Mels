<?php

namespace App\Filament\Resources\ProjectReportResource\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ProjectsByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Projects by Status';
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $data = [
            'completed' => Project::where('status', 'completed')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
            'cancelled' => Project::where('status', 'cancelled')->count(),
            'pending' => Project::where('status', 'pending')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Projects by Status',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#10B981', // green-500
                        '#3B82F6', // blue-500
                        '#F59E0B', // yellow-500
                        '#EF4444', // red-500
                        '#9CA3AF', // gray-400
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Completed', 'In Progress', 'On Hold', 'Cancelled', 'Pending'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getDescription(): ?string
    {
        return 'Distribution of projects by their current status';
    }
}
