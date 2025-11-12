@extends('layouts.app')

@section('content')
    <h2 class="text-center text-3xl font-bold text-gray-800 mb-8">Select a Candidate to View Analytics</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach ($analytics as $candidate)
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                <img src="{{ $candidate['image'] }}" class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-blue-100 mb-4">
                <h3 class="text-center text-xl font-semibold mb-2">{{ $candidate['candidate'] }}</h3>
                <p class="text-center text-gray-600 mb-4">Success Rate: <span class="font-bold text-green-600">{{ $candidate['success_rate'] }}%</span></p>
                <a href="{{ route('analytics.show', $candidate['slug']) }}" class="block text-center bg-blue-700 text-white py-2 rounded-lg hover:bg-blue-800 transition">View Analytics</a>
            </div>
        @endforeach
    </div>
@endsection
