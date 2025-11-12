@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center gap-6 mb-6">
            <img src="{{ $candidate['image'] }}" class="w-32 h-32 rounded-full object-cover">
            <div>
                <h2 class="text-3xl font-bold">{{ $candidate['name'] }}</h2>
                <p class="text-gray-600">{{ $candidate['arm'] }}</p>
            </div>
        </div>

        <h3 class="text-xl font-semibold mb-3">Projects</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($candidate['projects'] as $project)
                <a href="{{ route('projects.show', $project['id']) }}" class="block bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
                    <h4 class="text-lg font-semibold">{{ $project['title'] }}</h4>
                    <p class="text-gray-600">{{ $project['category'] }}</p>
                    <span class="text-sm {{ $project['status'] == 'Completed' ? 'text-green-600' : ($project['status'] == 'Ongoing' ? 'text-yellow-600' : 'text-red-600') }}">
                {{ $project['status'] }}
            </span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
