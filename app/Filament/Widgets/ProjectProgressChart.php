<?php

namespace App\Filament\Widgets;

use App\Models\ProjectUpdate;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class ProjectProgressChart extends ChartWidget
{
    protected static ?string $heading = 'Project Progress Over Time';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get data for the last 6 months
        $startDate = now()->subMonths(6)->startOfMonth();
        $endDate = now()->endOfMonth();

        // Get all project updates grouped by month
        $updates = ProjectUpdate::query()
            ->select(
                DB::raw('DATE_FORMAT(update_date, "%Y-%m") as month'),
                DB::raw('AVG(completion_percentage) as avg_completion')
            )
            ->whereBetween('update_date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Generate all months in the range
        $months = collect(\Carbon\CarbonPeriod::create($startDate, '1 month', $endDate))
            ->map(fn ($date) => $date->format('Y-m'));

        // Fill in the data with 0 for months with no updates
        $data = $months->mapWithKeys(function ($month) use ($updates) {
            $update = $updates->firstWhere('month', $month);
            return [$month => $update ? (float) $update->avg_completion : 0];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Average Completion %',
                    'data' => $data->values()->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->keys()->map(fn ($month) => \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "%"; }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.parsed.y + "%"; }',
                    ],
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        return true;
    }
}
