<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProjectStatsWidget;
use App\Filament\Widgets\ProjectsByCategoryChart;
use App\Filament\Widgets\ProjectProgressChart;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            ProjectStatsWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 4;
    }

    protected function getFooterWidgets(): array
    {
        return [
            ProjectsByCategoryChart::class,
            ProjectProgressChart::class,
        ];
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }

    public function getTitle(): string
    {
        return 'Millenial Circuit Dashboard';
    }
}
