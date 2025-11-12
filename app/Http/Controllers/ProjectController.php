<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($id)
    {
        $projects = collect(app(CandidateController::class)->index()->getData()['candidates'])
            ->flatMap(fn($c) => $c['projects']);
        $project = $projects->firstWhere('id', $id);

        $progress = [
            'Planning' => rand(70, 100),
            'Funding' => rand(60, 100),
            'Implementation' => rand(50, 100),
            'Monitoring' => rand(40, 100),
        ];

        return view('projects.show', compact('project', 'progress'));
    }
}

