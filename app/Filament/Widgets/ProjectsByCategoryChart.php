<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ProjectsByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Projects by Category';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $categories = [
            'infrastructure',
            'education',
            'health',
            'agriculture',
            'security',
            'employment',
            'youth_development',
            'women_empowerment',
            'others',
        ];

        $categoryData = [];
        $categoryColors = [];
        $categoryCounts = [];

        foreach ($categories as $category) {
            $count = Project::where('category', $category)->count();
            if ($count > 0) {
                $categoryData[] = ucfirst(str_replace('_', ' ', $category));
                $categoryCounts[] = $count;
                $categoryColors[] = $this->getCategoryColor($category);
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Projects by Category',
                    'data' => $categoryCounts,
                    'backgroundColor' => $categoryColors,
                    'borderColor' => $categoryColors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $categoryData,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
            ],
            'scales' => [
                'y' => [
                    'display' => false,
                ],
                'x' => [
                    'display' => false,
                ],
            ],
        ];
    }

    private function getCategoryColor(string $category): string
    {
        return match ($category) {
            'infrastructure' => '#3b82f6',    // blue-500
            'education' => '#10b981',         // emerald-500
            'health' => '#ef4444',            // red-500
            'agriculture' => '#84cc16',       // lime-500
            'security' => '#8b5cf6',          // violet-500
            'employment' => '#f59e0b',        // amber-500
            'youth_development' => '#ec4899', // pink-500
            'women_empowerment' => '#d946ef', // fuchsia-500
            default => '#6b7280',             // gray-500
        };
    }

    public static function canView(): bool
    {
        return true;
    }
}
