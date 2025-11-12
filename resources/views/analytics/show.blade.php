@extends('layouts.app')

@section('content')
    <div class="mb-10">
        <div class="flex items-center gap-6 mb-8">
            <img src="{{ $candidate['image'] }}" class="w-24 h-24 rounded-full object-cover border-4 border-blue-100">
            <div>
                <h2 class="text-3xl font-semibold">{{ $candidate['candidate'] }}</h2>
                <p class="text-gray-600">Total Shortlisted: {{ number_format($candidate['total_shortlisted']) }}</p>
                <p class="text-gray-600">Total Disbursed: {{ number_format($candidate['total_disbursed']) }}</p>
                <p class="text-green-600 font-bold">Success Rate: {{ $candidate['success_rate'] }}%</p>
            </div>
        </div>

        <div class="mb-10">
            <canvas id="performanceChart" height="100"></canvas>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Projects Overview</h3>
            <select id="filter" class="border rounded-md px-3 py-2">
                <option value="">All Arms</option>
                <option value="Executive">Executive</option>
                <option value="Legislative">Legislative</option>
                <option value="Judiciary">Judiciary</option>
            </select>
        </div>

        <div id="projects-list" class="space-y-4">
            @foreach ($candidate['projects'] as $project)
                @php
                    $progress = round(($project['disbursed'] / max($project['shortlisted'], 1)) * 100, 1);
                    $color = match(true) {
                        $progress >= 80 => 'bg-green-500',
                        $progress >= 50 => 'bg-yellow-500',
                        default => 'bg-red-500'
                    };
                    $projectSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $project['name']));
                @endphp
                <a href="{{ route('analytics.project', ['slug' => $candidate['slug'], 'projectSlug' => $projectSlug]) }}" 
                   class="block project bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow duration-200 transform hover:-translate-y-0.5" 
                   data-arm="{{ $project['arm'] }}">
                    <div class="flex justify-between mb-2">
                        <h4 class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition-colors">
                            {{ $project['name'] }}
                            <span class="inline-block ml-2 text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </span>
                        </h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                            $project['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                            ($project['status'] === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')
                        }}">
                            {{ str_replace('_', ' ', ucfirst($project['status'])) }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 h-4 rounded-full mb-2">
                        <div class="{{ $color }} h-4 rounded-full transition-all duration-500 ease-in-out" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($project['start_date'])->format('M Y') }} - {{ \Carbon\Carbon::parse($project['end_date'])->format('M Y') }}</span>
                        </div>
                        <div class="font-medium {{ $progress >= 80 ? 'text-green-600' : ($progress >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $progress }}% Complete
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        <span class="font-medium">Contractor:</span> {{ $project['contractor'] }}
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Timeline -->
        <div class="mt-10">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Project Timeline (by Status)</h3>
            <div class="flex flex-wrap gap-3">
                @foreach (collect($candidate['projects'])->groupBy('status') as $status => $group)
                    <div class="bg-white shadow p-4 rounded-lg w-full md:w-1/3">
                        <h4 class="font-semibold capitalize mb-2">{{ $status }}</h4>
                        <ul class="list-disc list-inside text-gray-700">
                            @foreach ($group as $proj)
                                <li>{{ $proj['name'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('performanceChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($candidate['by_arm']->keys()),
                datasets: [
                    { label: 'Shortlisted', data: @json($candidate['by_arm']->pluck('shortlisted')), backgroundColor: 'rgba(54, 162, 235, 0.6)' },
                    { label: 'Disbursed', data: @json($candidate['by_arm']->pluck('disbursed')), backgroundColor: 'rgba(75, 192, 192, 0.6)' }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
        });

        // Filter functionality
        document.getElementById('filter').addEventListener('change', function() {
            const value = this.value;
            document.querySelectorAll('.project').forEach(card => {
                card.style.display = !value || card.dataset.arm === value ? '' : 'none';
            });
        });
    </script>
@endsection
