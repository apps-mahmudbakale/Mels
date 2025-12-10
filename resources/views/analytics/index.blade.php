@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-center text-3xl font-bold text-gray-800 mb-8">Performance Analytics</h2>

        <!-- Filters -->
        <div class="flex justify-center gap-4 mb-10">
            <button class="filter-btn active px-6 py-2 rounded-full border border-blue-600 bg-blue-600 text-white hover:bg-blue-700 transition" data-filter="all">
                All Levels
            </button>
            @foreach(['federal', 'state', 'local'] as $level)
                <button class="filter-btn px-6 py-2 rounded-full border border-gray-300 text-gray-600 hover:border-blue-500 hover:text-blue-500 transition capitalize" data-filter="{{ $level }}">
                    {{ $level }}
                </button>
            @endforeach
        </div>

        @foreach($analytics as $tier => $arms)
            <div class="mb-12 tier-section" data-tier="{{ strtolower($tier) }}">
                <h3 class="text-2xl font-bold text-gray-700 mb-6 border-b pb-2 uppercase tracking-wide">{{ ucfirst($tier) }} Level</h3>
                
                @foreach($arms as $arm => $aspirants)
                    <div class="mb-8 pl-4 border-l-4 border-blue-500">
                        <h4 class="text-xl font-semibold text-blue-600 mb-4">{{ ucfirst($arm) }} Arm</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                            @foreach ($aspirants as $aspirant)
                                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1 relative overflow-hidden">
                                     @if($aspirant->party)
                                        <div class="absolute top-2 right-2 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center shadow-sm" title="{{ $aspirant->party->name }}">
                                            @if($aspirant->party->logo)
                                                <img src="{{ Storage::url($aspirant->party->logo) }}" class="w-6 h-6 object-contain">
                                            @else
                                                <span class="text-xs font-bold">{{ $aspirant->party->abbreviation }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <img src="{{ $aspirant->photo_path ? Storage::url($aspirant->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($aspirant->first_name.' '.$aspirant->last_name) }}" class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-blue-100 mb-4">
                                    
                                    <h3 class="text-center text-xl font-semibold mb-1">{{ $aspirant->first_name }} {{ $aspirant->last_name }}</h3>
                                    <p class="text-center text-sm text-gray-500 mb-3">{{ $aspirant->office ? $aspirant->office->name : 'Aspirant' }}</p>

                                    <div class="flex justify-between text-sm text-gray-600 mb-2 px-2">
                                        <span>Projects: {{ $aspirant->total_projects }}</span>
                                        <span>Completed: {{ $aspirant->completed_projects }}</span>
                                    </div>

                                    <p class="text-center text-gray-600 mb-4">Success Rate: <span class="font-bold {{ $aspirant->success_rate >= 70 ? 'text-green-600' : ($aspirant->success_rate >= 40 ? 'text-yellow-600' : 'text-red-600') }}">{{ $aspirant->success_rate }}%</span></p>
                                    
                                    <a href="{{ route('analytics.show', $aspirant->slug) }}" class="block text-center bg-blue-700 text-white py-2 rounded-lg hover:bg-blue-800 transition">View Analytics</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.filter-btn');
            const sections = document.querySelectorAll('.tier-section');

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Update button styles
                    buttons.forEach(b => {
                        b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                        b.classList.add('border-gray-300', 'text-gray-600');
                    });
                    btn.classList.remove('border-gray-300', 'text-gray-600');
                    btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');

                    const filter = btn.dataset.filter;

                    sections.forEach(section => {
                        if (filter === 'all' || section.dataset.tier === filter) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection
