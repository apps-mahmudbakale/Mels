@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">{{ $project['title'] }}</h2>
        <p class="text-gray-600 mb-3"><strong>Category:</strong> {{ $project['category'] }}</p>
        <p class="mb-6">
            <strong>Status:</strong>
            <span class="{{ $project['status'] == 'Completed' ? 'text-green-600' : ($project['status'] == 'Ongoing' ? 'text-yellow-600' : 'text-red-600') }}">
            {{ $project['status'] }}
        </span>
        </p>

        <h3 class="text-lg font-semibold mb-2">Progress Overview</h3>
        @foreach($progress as $stage => $percent)
            <div class="mb-3">
                <p class="text-gray-700 font-medium">{{ $stage }} - {{ $percent }}%</p>
                <div class="w-full bg-gray-200 h-3 rounded">
                    <div class="h-3 bg-blue-600 rounded" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
