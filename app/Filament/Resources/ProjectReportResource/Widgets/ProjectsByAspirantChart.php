<?php

namespace App\Filament\Resources\ProjectReportResource\Widgets;

use App\Models\Project;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class ProjectsByAspirantChart extends BarChartWidget
{
    protected static ?string $heading = 'Top Aspirants by Project Count';
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $data = Project::query()
            ->join('aspirants', 'projects.aspirant_id', '=', 'aspirants.id')
            ->select(
                DB::raw("CONCAT(aspirants.first_name, ' ', aspirants.last_name) as aspirant_name"),
                DB::raw('count(projects.id) as total')
            )
            ->groupBy('aspirants.first_name', 'aspirants.last_name')
            ->orderBy('total', 'desc')
            ->limit(8)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Projects by Aspirant',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#8B5CF6'
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('aspirant_name')->toArray(),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Top 8 aspirants by number of projects';
    }
}
