<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Project::query()
            ->with(['aspirant', 'office'])
            ->select('projects.*')
            ->join('aspirants', 'projects.aspirant_id', '=', 'aspirants.id')
            ->when($this->filters['aspirant_id'] ?? null, function ($query, $aspirantId) {
                $query->where('aspirant_id', $aspirantId);
            })
            ->when($this->filters['lga'] ?? null, function ($query, $lga) {
                $query->where('lga', $lga);
            })
            ->when($this->filters['office_id'] ?? null, function ($query, $officeId) {
                $query->where('office_id', $officeId);
            })
            ->when($this->filters['party'] ?? null, function ($query, $party) {
                $query->where('party', $party);
            })
            ->when($this->filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->filters['start_date'] ?? null, function ($query, $startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($this->filters['end_date'] ?? null, function ($query, $endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Aspirant',
            'LGA',
            'Office',
            'Party',
            'Status',
            'Completion %',
            'Estimated Cost',
            'Start Date',
            'Expected Completion',
            'Actual Completion',
            'Created At',
            'Last Updated',
        ];
    }

    public function map($project): array
    {
        $aspirantName = $project->aspirant 
            ? trim($project->aspirant->first_name . ' ' . $project->aspirant->last_name)
            : 'N/A';
            
        return [
            $project->id,
            $project->title,
            $aspirantName,
            $project->lga,
            $project->office->name ?? 'N/A',
            $project->party,
            ucfirst(str_replace('_', ' ', $project->status)),
            $project->completion_percentage . '%',
            '₦' . number_format($project->estimated_cost ?? 0, 2),
            $project->start_date?->format('Y-m-d') ?: 'N/A',
            $project->expected_completion_date?->format('Y-m-d') ?: 'N/A',
            $project->actual_completion_date?->format('Y-m-d') ?: 'N/A',
            $project->created_at->format('Y-m-d H:i:s'),
            $project->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D9EAD3']
                ]
            ],
            // Set column widths
            'A' => ['width' => 10],
            'B' => ['width' => 30],
            'C' => ['width' => 25],
            'D' => ['width' => 20],
            'E' => ['width' => 25],
            'F' => ['width' => 15],
            'G' => ['width' => 15],
            'H' => ['width' => 15],
            'I' => ['width' => 15],
            'J' => ['width' => 15],
            'K' => ['width' => 20],
            'L' => ['width' => 20],
            'M' => ['width' => 20],
            'N' => ['width' => 20],
        ];
    }
}
