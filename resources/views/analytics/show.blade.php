@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <a href="{{ route('analytics.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 transition">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Analytics
        </a>

        <div class="mb-10 bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center gap-6">
                <img src="{{ $aspirant->photo_path ? Storage::url($aspirant->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($aspirant->first_name.' '.$aspirant->last_name) }}" class="w-24 h-24 rounded-full object-cover border-4 border-blue-100">
                <div>
                    <h2 class="text-3xl font-semibold">{{ $aspirant->first_name }} {{ $aspirant->last_name }}</h2>
                    <p class="text-gray-600">{{ $aspirant->office ? $aspirant->office->name : 'Office' }} </p>
                    <p class="text-gray-600">Total Projects: {{ $aspirant->projects->count() }}</p>
                    <p class="text-gray-600">Completed: {{ $aspirant->projects->where('status', 'completed')->count() }}</p>
                    @php
                        $total = $aspirant->projects->count();
                        $completed = $aspirant->projects->where('status', 'completed')->count();
                        $successRate = $total > 0 ? round(($completed / $total) * 100) : 0;
                    @endphp
                    <p class="text-green-600 font-bold">Success Rate: {{ $successRate }}%</p>
                </div>
            </div>
        </div>

        <div class="mb-10 bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold mb-4">Performance by Status</h3>
            <canvas id="performanceChart" height="100"></canvas>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Projects Overview</h3>
            <select id="filter" class="border rounded-md px-3 py-2">
                <option value="">All Categories</option>
                @foreach($aspirant->projects->pluck('category')->unique() as $cat)
                    <option value="{{ $cat }}">{{ ucfirst(str_replace('_', ' ', $cat)) }}</option>
                @endforeach
            </select>
        </div>

        <div id="projects-list" class="space-y-4">
            @foreach ($aspirant->projects as $project)
                @php
                    $progress = $project->completion_percentage;
                    $color = match(true) {
                        $progress >= 80 => 'bg-green-500',
                        $progress >= 50 => 'bg-yellow-500',
                        default => 'bg-red-500'
                    };
                    // Ensure we have a slug or use ID if slug missing
                    $projectSlug = Str::slug($project->title); // Temporary if project has no slug column, using title
                @endphp
                {{-- Link to project details --}}
                <a href="{{ route('analytics.project', ['slug' => $aspirant->slug, 'project' => $project->id]) }}" 
                   class="block project bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow duration-200" 
                   data-category="{{ $project->category }}">
                    <div class="flex justify-between mb-2">
                        <h4 class="text-lg font-semibold text-gray-800">
                            {{ $project->title }}
                        </h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                            $project->status === 'completed' ? 'bg-green-100 text-green-800' : 
                            ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')
                        }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>
                    
                    <div class="w-full bg-gray-200 h-4 rounded-full mb-2">
                        <div class="{{ $color }} h-4 rounded-full transition-all duration-500 ease-in-out" style="width: {{ $progress }}%"></div>
                    </div>
                    
                    <div class="flex justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="mr-4"><strong>Budget:</strong> ₦{{ number_format($project->estimated_cost) }}</span>
                            @if($project->start_date)
                                <span>{{ $project->start_date->format('M Y') }} - {{ $project->expected_completion_date ? $project->expected_completion_date->format('M Y') : 'Ongoing' }}</span>
                            @endif
                        </div>
                        <div class="font-medium {{ $progress >= 80 ? 'text-green-600' : ($progress >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $progress }}% Complete
                        </div>
                    </div>
                    
                    @if($project->project_updates->count() > 0)
                        <div class="mt-4 pt-4 border-t text-sm">
                            <h5 class="font-semibold mb-2">Latest Update:</h5>
                            <p class="text-gray-600">{{ Str::limit($project->project_updates->last()->description, 150) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $project->project_updates->last()->update_date->format('d M, Y') }}</p>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('performanceChart');
        
        // Prepare data from Blade
        const statusCounts = @json($aspirant->projects->groupBy('status')->map->count());
        const labels = Object.keys(statusCounts).map(s => s.replace('_', ' ').toUpperCase());
        const data = Object.values(statusCounts);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { 
                        label: 'Projects Count', 
                        data: data, 
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(255, 99, 132, 0.6)'
                        ]
                    }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        // Filter functionality
        document.getElementById('filter').addEventListener('change', function() {
            const value = this.value;
            document.querySelectorAll('.project').forEach(card => {
                card.style.display = !value || card.dataset.category === value ? '' : 'none';
            });
        });
    </script>
@endsection
