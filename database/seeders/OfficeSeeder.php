<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $offices = [
            // Federal Offices
            ['name' => 'President', 'level' => 'federal', 'sort_order' => 1],
            ['name' => 'Vice President', 'level' => 'federal', 'sort_order' => 2],
            ['name' => 'Senate President', 'level' => 'federal', 'sort_order' => 3],
            ['name' => 'Speaker of the House', 'level' => 'federal', 'sort_order' => 4],
            ['name' => 'Senator', 'level' => 'federal', 'sort_order' => 5],
            ['name' => 'House of Representatives', 'level' => 'federal', 'sort_order' => 6],
            
            // State Offices
            ['name' => 'Governor', 'level' => 'state', 'sort_order' => 10],
            ['name' => 'Deputy Governor', 'level' => 'state', 'sort_order' => 11],
            ['name' => 'Speaker, State House of Assembly', 'level' => 'state', 'sort_order' => 12],
            ['name' => 'State House of Assembly', 'level' => 'state', 'sort_order' => 13],
            
            // Local Government Offices
            ['name' => 'Local Government Chairman', 'level' => 'local', 'sort_order' => 20],
            ['name' => 'Vice Chairman', 'level' => 'local', 'sort_order' => 21],
            ['name' => 'Councillor', 'level' => 'local', 'sort_order' => 22],
            ['name' => 'Supervisory Councillor', 'level' => 'local', 'sort_order' => 23],
        ];

        foreach ($offices as $office) {
            Office::firstOrCreate(
                ['name' => $office['name']],
                array_merge($office, [
                    'slug' => \Illuminate\Support\Str::slug($office['name']),
                    'description' => $office['name'] . ' Office',
                    'is_active' => true
                ])
            );
        }
    }
}
