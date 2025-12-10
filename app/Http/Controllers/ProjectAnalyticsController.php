<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectAnalyticsController extends Controller
{
    public function index()
    {
        $aspirants = \App\Models\Aspirant::with(['office', 'party', 'projects'])
            ->get();

        // Calculate stats for each aspirant
        $aspirants->each(function ($aspirant) {
            $totalProjects = $aspirant->projects->count();
            $completedProjects = $aspirant->projects->where('status', 'completed')->count();
            
            $aspirant->total_projects = $totalProjects;
            $aspirant->completed_projects = $completedProjects;
            $aspirant->success_rate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100) : 0;
        });

        // Group by Tier (Level) and then Arm (Type)
        $groupedAnalytics = $aspirants->groupBy([
            fn ($item) => $item->office?->level ?? 'Others',
            fn ($item) => $item->office?->type ?? 'Others',
        ]);

        // Order mapping for display
        $tierOrder = ['federal', 'state', 'local', 'Others'];
        $armOrder = ['executive', 'legislative', 'judiciary', 'Others'];

        $sortedAnalytics = collect([]);

        foreach ($tierOrder as $tier) {
            if (isset($groupedAnalytics[$tier])) {
                $sortedArms = collect([]);
                foreach ($armOrder as $arm) {
                    if (isset($groupedAnalytics[$tier][$arm])) {
                        $sortedArms[$arm] = $groupedAnalytics[$tier][$arm];
                    }
                }
                // Add remaining arms if any
                foreach ($groupedAnalytics[$tier] as $arm => $data) {
                    if (!in_array($arm, $armOrder)) {
                        $sortedArms[$arm] = $data;
                    }
                }
                $sortedAnalytics[$tier] = $sortedArms;
            }
        }
        
        // Add remaining tiers
        foreach ($groupedAnalytics as $tier => $data) {
            if (!in_array($tier, $tierOrder)) {
                $sortedAnalytics[$tier] = $data;
            }
        }

        return view('analytics.index', ['analytics' => $sortedAnalytics]);
    }

    public function show($slug)
    {
        $aspirant = \App\Models\Aspirant::where('slug', $slug) // Assuming Aspirant has slug, otherwise use ID or add slug
            ->with(['office', 'party', 'projects.project_updates'])
            ->firstOrFail();

        return view('analytics.show', compact('aspirant'));
    }

    public function project($slug, $project)
    {
        $aspirant = \App\Models\Aspirant::where('slug', $slug)->firstOrFail();
        
        // Find project by ID
        $projectModel = \App\Models\Project::with('project_updates')
            ->where('id', $project)
            ->where('aspirant_id', $aspirant->id)
            ->firstOrFail();

        return view('analytics.project', [
            'aspirant' => $aspirant,
            'project' => $projectModel
        ]);
    }
}
