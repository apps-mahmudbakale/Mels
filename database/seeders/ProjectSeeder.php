<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate the projects table and reset auto-increment
        Project::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create 100 projects with their updates
        $projects = Project::factory()
            ->count(100)
            ->create();

        // Update project counts and statistics
        $this->updateProjectStatistics();
    }

    /**
     * Update project statistics after seeding.
     */
    protected function updateProjectStatistics(): void
    {
        // Update project status counts in the database
        $statuses = ProjectStatus::cases();
        
        foreach ($statuses as $status) {
            $count = Project::where('status', $status->value)->count();
            \Illuminate\Support\Facades\Cache::put("projects.status.{$status->value}", $count, now()->addDay());
        }
        
        // Update total projects count
        \Illuminate\Support\Facades\Cache::put('projects.total', Project::count(), now()->addDay());
        
        // Update completion rate
        $completed = Project::where('status', ProjectStatus::COMPLETED->value)->count();
        $total = Project::count();
        $completionRate = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
        \Illuminate\Support\Facades\Cache::put('projects.completion_rate', $completionRate, now()->addDay());
        
        // Update average completion time
        $avgCompletionDays = Project::where('status', ProjectStatus::COMPLETED)
            ->selectRaw('AVG(DATEDIFF(actual_completion_date, start_date)) as avg_days')
            ->value('avg_days');
        \Illuminate\Support\Facades\Cache::put('projects.avg_completion_days', (int)$avgCompletionDays, now()->addDay());
    }
}
