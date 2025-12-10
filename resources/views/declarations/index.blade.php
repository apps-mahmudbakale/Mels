@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-center text-3xl font-bold text-gray-800 mb-8">Declarations & Updates</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($declarations as $declaration)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col h-full">
                    @if($declaration->featured_image)
                        <img src="{{ Storage::url($declaration->featured_image) }}" class="w-full h-48 object-cover">
                    @endif
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center mb-2">
                             @if($declaration->aspirant && $declaration->aspirant->photo_path)
                                <img src="{{ Storage::url($declaration->aspirant->photo_path) }}" class="w-8 h-8 rounded-full object-cover mr-2">
                            @endif
                            <span class="text-sm text-blue-600 font-semibold">
                                {{ $declaration->aspirant ? $declaration->aspirant->first_name . ' ' . $declaration->aspirant->last_name : 'Admin' }}
                            </span>
                            <span class="mx-2 text-gray-400">•</span>
                            <span class="text-sm text-gray-500">{{ $declaration->published_at ? $declaration->published_at->format('M d, Y') : $declaration->created_at->format('M d, Y') }}</span>
                        </div>

                        <h3 class="text-xl font-bold mb-2 text-gray-800 line-clamp-2">{{ $declaration->title }}</h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $declaration->excerpt ?? Str::limit(strip_tags($declaration->content), 100) }}
                        </p>
                        
                        <div class="mt-auto">
                            <a href="{{ route('declarations.show', $declaration->slug) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Read Declaration</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">No declarations published yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $declarations->links() }}
        </div>
    </div>
@endsection
