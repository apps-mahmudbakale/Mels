@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back button -->
    <a href="{{ route('analytics.show', $aspirant->slug) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back to {{ $aspirant->first_name }} {{ $aspirant->last_name }}
    </a>

    <!-- Project Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                <p class="text-gray-600">{{ $project->category_label ?? ucfirst(str_replace('_', ' ', $project->category)) }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ 
                    $project->status === 'completed' ? 'bg-green-100 text-green-800' : 
                    ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')
                }}">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </span>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-6">
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-gray-700">Project Completion</span>
                <span class="text-sm font-medium {{ 
                    $project->completion_percentage >= 80 ? 'text-green-600' : 
                    ($project->completion_percentage >= 50 ? 'text-yellow-600' : 'text-red-600')
                }}">
                    {{ $project->completion_percentage }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="h-2.5 rounded-full {{ 
                    $project->completion_percentage >= 80 ? 'bg-green-500' : 
                    ($project->completion_percentage >= 50 ? 'bg-yellow-500' : 'bg-red-500')
                }}" style="width: {{ $project->completion_percentage }}%"></div>
            </div>
        </div>

        <!-- Project Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Beneficiaries</p>
                <p class="text-2xl font-semibold">{{ $project->beneficiaries ? $project->beneficiaries : 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Location</p>
                <p class="text-2xl font-semibold">{{ $project->location ?? 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Budget</p>
                <p class="text-2xl font-semibold">₦{{ number_format($project->estimated_cost) }}</p>
            </div>
        </div>

        <!-- Description -->
         <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3">Description</h3>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($project->description)) !!}
            </div>
        </div>

        <!-- Project Updates -->
        <div>
            <h3 class="text-xl font-bold mb-4 border-b pb-2">Project Updates</h3>
            
            @if($project->project_updates->count() > 0)
                <div class="flow-root">
                    <ul class="space-y-4">
                        @foreach($project->project_updates->sortByDesc('update_date') as $update)
                            <li class="relative pb-6">
                                @if(!$loop->last)
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></div>
                                @endif
                                <div class="relative flex items-start group">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ 
                                        $update->status === 'completed' ? 'bg-green-500' : 'bg-blue-500' 
                                    }}">
                                       <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                       </svg>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="flex justify-between items-center mb-1">
                                            <h4 class="font-medium text-gray-900">{{ $update->title }}</h4>
                                            <span class="text-sm text-gray-500">{{ $update->update_date->format('M j, Y') }}</span>
                                        </div>
                                        <p class="text-gray-700 text-sm mb-2">{{ $update->description }}</p>
                                        
                                        @if($update->image_path)
                                            <img src="{{ Storage::url($update->image_path) }}" class="w-full max-w-md rounded-lg mb-2 shadow-sm border">
                                        @endif
                                        
                                        <div class="flex items-center text-xs text-gray-500 space-x-4">
                                            @if($update->amount_spent > 0)
                                                <span>Spent: ₦{{ number_format($update->amount_spent) }}</span>
                                            @endif
                                            <span>Progress: {{ $update->completion_percentage }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-gray-500 italic">No updates available for this project yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
