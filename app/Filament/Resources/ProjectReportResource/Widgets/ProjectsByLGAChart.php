<?php

namespace App\Filament\Resources\ProjectReportResource\Widgets;

use App\Models\Project;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class ProjectsByLGAChart extends BarChartWidget
{
    protected static ?string $heading = 'Projects by LGA';
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Project::query()
            ->select('lga', DB::raw('count(*) as total'))
            ->groupBy('lga')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Projects by LGA',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#8B5CF6'
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('lga')->toArray(),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Top 10 LGAs by number of projects';
    }
}
