<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = [
            [
                'id' => 1,
                'name' => 'Muhammadu Buhari',
                'image' => '/images/buhari.jpg',
                'arm' => 'Executive',
                'projects' => [
                    ['id' => 1, 'title' => 'GEEP Soft Loan Program', 'category' => 'Economic Empowerment', 'status' => 'Completed'],
                    ['id' => 2, 'title' => 'N-Power Youth Employment', 'category' => 'Social Welfare', 'status' => 'Ongoing'],
                    ['id' => 3, 'title' => 'Anchor Borrowers Program', 'category' => 'Agriculture', 'status' => 'Completed'],
                    ['id' => 4, 'title' => 'TraderMoni', 'category' => 'Microfinance', 'status' => 'Completed'],
                    ['id' => 5, 'title' => 'Railway Modernization', 'category' => 'Infrastructure', 'status' => 'Ongoing'],
                ],
            ],
            [
                'id' => 2,
                'name' => 'Bola Ahmed Tinubu',
                'image' => '/images/tinubu.jpg',
                'arm' => 'Executive',
                'projects' => [
                    ['id' => 6, 'title' => 'Renewed Hope Housing Scheme', 'category' => 'Infrastructure', 'status' => 'Ongoing'],
                    ['id' => 7, 'title' => 'Student Loan Act Implementation', 'category' => 'Education', 'status' => 'Pending'],
                    ['id' => 8, 'title' => 'National Digital Economy Plan', 'category' => 'Technology', 'status' => 'Ongoing'],
                    ['id' => 9, 'title' => 'Subsidy Removal Relief Fund', 'category' => 'Welfare', 'status' => 'Ongoing'],
                    ['id' => 10, 'title' => 'Agric Value Chain Program', 'category' => 'Agriculture', 'status' => 'Pending'],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Nasir El-Rufai',
                'image' => '/images/elrufai.jpg',
                'arm' => 'State Government',
                'projects' => [
                    ['id' => 11, 'title' => 'Kaduna Urban Renewal Project', 'category' => 'Infrastructure', 'status' => 'Completed'],
                    ['id' => 12, 'title' => 'Education Reform Scheme', 'category' => 'Education', 'status' => 'Completed'],
                    ['id' => 13, 'title' => 'ICT Transformation Project', 'category' => 'Technology', 'status' => 'Ongoing'],
                    ['id' => 14, 'title' => 'Healthcare Access Expansion', 'category' => 'Health', 'status' => 'Ongoing'],
                    ['id' => 15, 'title' => 'Youth Empowerment Kaduna', 'category' => 'Social Welfare', 'status' => 'Completed'],
                ],
            ],
            [
                'id' => 4,
                'name' => 'Aminu Waziri Tambuwal',
                'image' => '/images/aminu.jpg',
                'arm' => 'State Government',
                'projects' => [
                    ['id' => 16, 'title' => 'Sokoto Solar Power Project', 'category' => 'Energy', 'status' => 'Ongoing'],
                    ['id' => 17, 'title' => 'Teacher Training Initiative', 'category' => 'Education', 'status' => 'Completed'],
                    ['id' => 18, 'title' => 'Rural Water Supply', 'category' => 'Infrastructure', 'status' => 'Ongoing'],
                    ['id' => 19, 'title' => 'Women Empowerment Fund', 'category' => 'Welfare', 'status' => 'Completed'],
                    ['id' => 20, 'title' => 'Primary Healthcare Upgrade', 'category' => 'Health', 'status' => 'Ongoing'],
                ],
            ],
            [
                'id' => 5,
                'name' => 'Rabiu Musa Kwankwaso',
                'image' => '/images/kwankwaso.jpg',
                'arm' => 'State Government',
                'projects' => [
                    ['id' => 21, 'title' => 'Kwankwasiyya Education Scholarship', 'category' => 'Education', 'status' => 'Completed'],
                    ['id' => 22, 'title' => 'Youth Entrepreneurship Scheme', 'category' => 'Economic Empowerment', 'status' => 'Completed'],
                    ['id' => 23, 'title' => 'Healthcare Revamp Project', 'category' => 'Health', 'status' => 'Ongoing'],
                    ['id' => 24, 'title' => 'Kano ICT Innovation Hub', 'category' => 'Technology', 'status' => 'Ongoing'],
                    ['id' => 25, 'title' => 'Housing for All Program', 'category' => 'Infrastructure', 'status' => 'Pending'],
                ],
            ],
        ];

        return view('candidates.index', compact('candidates'));
    }

    public function show($id)
    {
        $candidate = collect($this->index()->getData()['candidates'])->firstWhere('id', $id);
        return view('candidates.show', compact('candidate'));
    }
}

