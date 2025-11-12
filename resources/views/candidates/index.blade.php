@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold mb-4 text-center">Select a Candidate</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($candidates as $candidate)
                <a href="{{ route('candidates.show', $candidate['id']) }}" class="bg-white rounded-lg shadow-md p-4 hover:shadow-xl transition">
                    <img src="{{ $candidate['image'] }}" class="rounded-lg h-48 w-full object-cover mb-3">
                    <h3 class="text-lg font-semibold">{{ $candidate['name'] }}</h3>
                    <p class="text-gray-500">{{ $candidate['arm'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endsection
