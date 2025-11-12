@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back button -->
    <a href="{{ route('analytics.show', $candidate['slug']) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back to {{ $candidate['candidate'] }}'s Projects
    </a>

    <!-- Project Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $project['name'] }}</h1>
                <p class="text-gray-600">{{ $project['arm'] }} • {{ ucfirst(str_replace('_', ' ', $project['status'])) }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ 
                    $project['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                    ($project['status'] === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')
                }}">
                    {{ str_replace('_', ' ', ucfirst($project['status'])) }}
                </span>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-6">
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-gray-700">Project Completion</span>
                <span class="text-sm font-medium {{ 
                    $project['progress'] >= 80 ? 'text-green-600' : 
                    ($project['progress'] >= 50 ? 'text-yellow-600' : 'text-red-600')
                }}">
                    {{ $project['progress'] }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="h-2.5 rounded-full {{ 
                    $project['progress'] >= 80 ? 'bg-green-500' : 
                    ($project['progress'] >= 50 ? 'bg-yellow-500' : 'bg-red-500')
                }}" style="width: {{ $project['progress'] }}%"></div>
            </div>
        </div>

        <!-- Project Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Shortlisted</p>
                <p class="text-2xl font-semibold">{{ number_format($project['shortlisted']) }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Disbursed</p>
                <p class="text-2xl font-semibold">{{ number_format($project['disbursed']) }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Budget</p>
                <p class="text-2xl font-semibold">₦{{ number_format($project['budget']) }}</p>
            </div>
        </div>

        <!-- Project Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-3">Project Details</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500">Contractor</p>
                            <p class="font-medium">{{ $project['contractor'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500">Timeline</p>
                            <p class="font-medium">
                                {{ \Carbon\Carbon::parse($project['start_date'])->format('M j, Y') }} - 
                                {{ \Carbon\Carbon::parse($project['end_date'])->format('M j, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3">Project Timeline</h3>
                <div class="flow-root">
                    <ul class="space-y-4">
                        @foreach($steps as $step)
                            <li class="relative pb-6">
                                @if(!$loop->last)
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></div>
                                @endif
                                <div class="relative flex items-start group">
                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        @if($step['progress'] <= $project['progress'])
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-4
                                        @if($step['progress'] <= $project['progress'])
                                            text-gray-900
                                        @else
                                            text-gray-400
                                        @endif">
                                        <h4 class="font-medium">{{ $step['title'] }}</h4>
                                        <p class="text-sm">{{ $step['description'] }}</p>
                                        <p class="text-xs mt-1">{{ $step['progress'] }}% Complete</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Summary -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Project Summary</h3>
        <div class="prose max-w-none">
            <p>This project is part of {{ $candidate['candidate'] }}'s initiative to improve public services in Nigeria. The project has made significant progress with {{ $project['disbursed'] }} beneficiaries already reached out of {{ $project['shortlisted'] }} targeted.</p>
            
            @if($project['status'] === 'completed')
                <p class="mt-4">✅ Successfully completed on {{ \Carbon\Carbon::parse($project['end_date'])->format('F j, Y') }}. The project has been fully implemented and all targets have been met.</p>
            @elseif($project['status'] === 'in_progress')
                <p class="mt-4">🔄 Currently in progress. The project is {{ $project['progress'] }}% complete and on track to be finished by {{ \Carbon\Carbon::parse($project['end_date'])->format('F j, Y') }}.</p>
            @else
                <p class="mt-4">📅 Project is in the planning phase. Expected to start soon and be completed by {{ \Carbon\Carbon::parse($project['end_date'])->format('F j, Y') }}.</p>
            @endif
        </div>
    </div>
</div>
@endsection
