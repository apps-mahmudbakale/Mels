<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectAnalyticsController extends Controller
{
    protected array $analytics = [];

    public function __construct()
    {
        $this->analytics = [
            [
                'candidate' => 'Muhammadu Buhari',
                'slug' => 'muhammadu-buhari',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/5/52/Muhammadu_Buhari_%28cropped%29.jpg',
                'projects' => [
                    ['name' => 'GEEP Soft Loan Program', 'arm' => 'Executive', 'shortlisted' => 98000, 'disbursed' => 3166, 'status' => 'completed', 'budget' => 500000000, 'contractor' => 'BOI Nigeria', 'start_date' => '2016-03-01', 'end_date' => '2021-08-01'],
                    ['name' => 'N-Power Employment Scheme', 'arm' => 'Executive', 'shortlisted' => 500000, 'disbursed' => 450000, 'status' => 'completed', 'budget' => 2000000000, 'contractor' => 'Ministry of Humanitarian Affairs', 'start_date' => '2016-12-01', 'end_date' => '2020-09-01'],
                    ['name' => 'Digital Judiciary Reform', 'arm' => 'Judiciary', 'shortlisted' => 800, 'disbursed' => 400, 'status' => 'in_progress', 'budget' => 100000000, 'contractor' => 'NITDA', 'start_date' => '2019-01-01', 'end_date' => '2025-01-01'],
                    ['name' => 'Constituency Infrastructure Fund', 'arm' => 'Legislative', 'shortlisted' => 109, 'disbursed' => 90, 'status' => 'in_progress', 'budget' => 750000000, 'contractor' => 'Federal Works Agency', 'start_date' => '2018-05-01', 'end_date' => '2025-12-01'],
                    ['name' => 'Healthcare Upgrade Program', 'arm' => 'Executive', 'shortlisted' => 2000, 'disbursed' => 1500, 'status' => 'completed', 'budget' => 350000000, 'contractor' => 'NHIS', 'start_date' => '2017-01-01', 'end_date' => '2021-01-01'],
                ],
            ],
            [
                'candidate' => 'Bola Ahmed Tinubu',
                'slug' => 'bola-ahmed-tinubu',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/2/2d/Bola_Tinubu_portrait.jpg',
                'projects' => [
                    ['name' => 'Digital Nigeria Initiative', 'arm' => 'Executive', 'shortlisted' => 20000, 'disbursed' => 12000, 'status' => 'in_progress', 'budget' => 800000000, 'contractor' => 'Galaxy Backbone Ltd', 'start_date' => '2023-01-15', 'end_date' => '2026-06-01'],
                    ['name' => 'Smart Judiciary Reform', 'arm' => 'Judiciary', 'shortlisted' => 800, 'disbursed' => 500, 'status' => 'planning', 'budget' => 100000000, 'contractor' => 'NITDA', 'start_date' => '2024-01-01', 'end_date' => '2027-01-01'],
                    ['name' => 'Constituency Youth Program', 'arm' => 'Legislative', 'shortlisted' => 15000, 'disbursed' => 10000, 'status' => 'completed', 'budget' => 500000000, 'contractor' => 'Youth Development Council', 'start_date' => '2023-04-01', 'end_date' => '2024-08-01'],
                    ['name' => 'Healthcare Renewal', 'arm' => 'Executive', 'shortlisted' => 10000, 'disbursed' => 8000, 'status' => 'completed', 'budget' => 700000000, 'contractor' => 'Federal Ministry of Health', 'start_date' => '2023-06-01', 'end_date' => '2025-01-01'],
                    ['name' => 'Infrastructure Drive', 'arm' => 'Executive', 'shortlisted' => 5000, 'disbursed' => 2500, 'status' => 'in_progress', 'budget' => 1200000000, 'contractor' => 'FEC Works', 'start_date' => '2023-05-01', 'end_date' => '2026-12-01'],
                ],
            ],
            [
                'candidate' => 'Nasir El-Rufai',
                'slug' => 'nasir-el-rufai',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/3/36/Nasir_El-Rufai_2019.jpg',
                'projects' => [
                    ['name' => 'Kaduna Smart City', 'arm' => 'Executive', 'shortlisted' => 10000, 'disbursed' => 8000, 'status' => 'in_progress', 'budget' => 950000000, 'contractor' => 'KadICT Hub', 'start_date' => '2021-01-01', 'end_date' => '2025-12-01'],
                    ['name' => 'Education Reform Project', 'arm' => 'Executive', 'shortlisted' => 5000, 'disbursed' => 4500, 'status' => 'completed', 'budget' => 400000000, 'contractor' => 'SUBEB Kaduna', 'start_date' => '2020-05-01', 'end_date' => '2023-01-01'],
                    ['name' => 'Judicial Review Commission', 'arm' => 'Judiciary', 'shortlisted' => 200, 'disbursed' => 100, 'status' => 'planning', 'budget' => 100000000, 'contractor' => 'Kaduna Judiciary', 'start_date' => '2022-01-01', 'end_date' => '2026-01-01'],
                    ['name' => 'Health Infrastructure Boost', 'arm' => 'Executive', 'shortlisted' => 3000, 'disbursed' => 2500, 'status' => 'in_progress', 'budget' => 350000000, 'contractor' => 'Ministry of Health Kaduna', 'start_date' => '2021-02-01', 'end_date' => '2024-06-01'],
                    ['name' => 'Legislative Town Halls', 'arm' => 'Legislative', 'shortlisted' => 100, 'disbursed' => 80, 'status' => 'completed', 'budget' => 50000000, 'contractor' => 'Kaduna Assembly', 'start_date' => '2020-01-01', 'end_date' => '2022-01-01'],
                ],
            ],
            [
                'candidate' => 'Aminu Waziri Tambuwal',
                'slug' => 'aminu-waziri-tambuwal',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/8/8a/Aminu_Waziri_Tambuwal.jpg',
                'projects' => [
                    ['name' => 'Agricultural Boost Scheme', 'arm' => 'Executive', 'shortlisted' => 15000, 'disbursed' => 12000, 'status' => 'completed', 'budget' => 500000000, 'contractor' => 'Sokoto Agric Board', 'start_date' => '2019-01-01', 'end_date' => '2022-06-01'],
                    ['name' => 'Judiciary Training Program', 'arm' => 'Judiciary', 'shortlisted' => 300, 'disbursed' => 150, 'status' => 'in_progress', 'budget' => 80000000, 'contractor' => 'Sokoto Judiciary', 'start_date' => '2021-05-01', 'end_date' => '2025-01-01'],
                    ['name' => 'Legislative Constituency Projects', 'arm' => 'Legislative', 'shortlisted' => 400, 'disbursed' => 300, 'status' => 'in_progress', 'budget' => 100000000, 'contractor' => 'Sokoto Assembly', 'start_date' => '2020-05-01', 'end_date' => '2024-01-01'],
                    ['name' => 'Education Empowerment Fund', 'arm' => 'Executive', 'shortlisted' => 5000, 'disbursed' => 4000, 'status' => 'completed', 'budget' => 250000000, 'contractor' => 'Sokoto Education Board', 'start_date' => '2020-06-01', 'end_date' => '2023-06-01'],
                    ['name' => 'Healthcare Development Plan', 'arm' => 'Executive', 'shortlisted' => 2000, 'disbursed' => 1500, 'status' => 'in_progress', 'budget' => 350000000, 'contractor' => 'Sokoto Health Agency', 'start_date' => '2021-01-01', 'end_date' => '2025-12-01'],
                ],
            ],
            [
                'candidate' => 'Rabiu Musa Kwankwaso',
                'slug' => 'rabiu-musa-kwankwaso',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/0/04/Rabiu_Musa_Kwankwaso.jpg',
                'projects' => [
                    ['name' => 'Kano Urban Renewal', 'arm' => 'Executive', 'shortlisted' => 10000, 'disbursed' => 8000, 'status' => 'in_progress', 'budget' => 1200000000, 'contractor' => 'Kano Works Agency', 'start_date' => '2022-01-01', 'end_date' => '2026-12-01'],
                    ['name' => 'Free Education Program', 'arm' => 'Executive', 'shortlisted' => 20000, 'disbursed' => 18000, 'status' => 'completed', 'budget' => 1000000000, 'contractor' => 'Kano Education Board', 'start_date' => '2021-05-01', 'end_date' => '2024-05-01'],
                    ['name' => 'Judicial Review Project', 'arm' => 'Judiciary', 'shortlisted' => 400, 'disbursed' => 200, 'status' => 'planning', 'budget' => 50000000, 'contractor' => 'Kano Judiciary', 'start_date' => '2022-01-01', 'end_date' => '2026-01-01'],
                    ['name' => 'Constituency Empowerment Scheme', 'arm' => 'Legislative', 'shortlisted' => 800, 'disbursed' => 600, 'status' => 'in_progress', 'budget' => 150000000, 'contractor' => 'Kano Assembly', 'start_date' => '2022-01-01', 'end_date' => '2025-06-01'],
                    ['name' => 'Agricultural Mechanization Project', 'arm' => 'Executive', 'shortlisted' => 3000, 'disbursed' => 2500, 'status' => 'completed', 'budget' => 450000000, 'contractor' => 'Kano Agric Agency', 'start_date' => '2021-01-01', 'end_date' => '2024-12-01'],
                ],
            ],
        ];

        foreach ($this->analytics as &$candidate) {
            $candidate['total_shortlisted'] = collect($candidate['projects'])->sum('shortlisted');
            $candidate['total_disbursed'] = collect($candidate['projects'])->sum('disbursed');
            $candidate['success_rate'] = round(($candidate['total_disbursed'] / $candidate['total_shortlisted']) * 100, 2);
            $candidate['by_arm'] = collect($candidate['projects'])->groupBy('arm')->map(fn($g) => [
                'shortlisted' => $g->sum('shortlisted'),
                'disbursed' => $g->sum('disbursed'),
            ]);
        }
    }

    public function index()
    {
        $analytics = $this->analytics;
        return view('analytics.index', compact('analytics'));
    }

    public function show($slug)
    {
        $candidate = collect($this->analytics)->firstWhere('slug', $slug);
        if (!$candidate) abort(404);
        return view('analytics.show', compact('candidate'));
    }

    public function project($slug, $projectSlug)
    {
        $candidate = collect($this->analytics)->firstWhere('slug', $slug);
        if (!$candidate) abort(404);

        // Normalize the project slug for comparison
        $normalizedSlug = strtolower(urldecode($projectSlug));
        
        // Try to find the project by matching the slug
        $project = collect($candidate['projects'])->first(function($item) use ($normalizedSlug) {
            $itemSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $item['name']));
            return $itemSlug === $normalizedSlug;
        });
        
        // If not found by slug, try a more flexible search by name (case insensitive, ignoring special chars)
        if (!$project) {
            $project = collect($candidate['projects'])->first(function($item) use ($normalizedSlug) {
                $itemName = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $item['name']));
                return $itemName === $normalizedSlug;
            });
        }
        
        if (!$project) {
            // Log the error for debugging
            \Log::error('Project not found', [
                'candidate' => $candidate['candidate'],
                'projectSlug' => $projectSlug,
                'availableProjects' => collect($candidate['projects'])->pluck('name')
            ]);
            abort(404, 'Project not found');
        }

        $steps = [
            ['title' => 'Planning', 'description' => 'Scope, objectives, and budgets were defined.', 'progress' => 25],
            ['title' => 'Approval', 'description' => 'Proposal approved by government authority.', 'progress' => 50],
            ['title' => 'Funding', 'description' => 'Funds released for execution.', 'progress' => 75],
            ['title' => 'Completion', 'description' => 'Project delivered and commissioned.', 'progress' => 100],
        ];

        $progress = round(($project['disbursed'] / max($project['shortlisted'], 1)) * 100, 1);
        $project['progress'] = $progress;

        return view('analytics.project', compact('candidate', 'project', 'steps'));
    }
}
