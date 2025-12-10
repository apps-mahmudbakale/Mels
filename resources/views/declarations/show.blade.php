@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <a href="{{ route('declarations.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 transition">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Declarations
        </a>

        <article class="bg-white rounded-2xl shadow-xl overflow-hidden">
            @if($declaration->featured_image)
                <div class="h-64 sm:h-96 w-full relative">
                     <img src="{{ Storage::url($declaration->featured_image) }}" class="w-full h-full object-cover">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                     <div class="absolute bottom-0 left-0 p-8 text-white">
                        <h1 class="text-3xl sm:text-4xl font-bold mb-2">{{ $declaration->title }}</h1>
                        <div class="flex items-center space-x-4">
                             @if($declaration->aspirant && $declaration->aspirant->photo_path)
                                <img src="{{ Storage::url($declaration->aspirant->photo_path) }}" class="w-10 h-10 rounded-full object-cover border-2 border-white">
                            @endif
                            <div>
                                <p class="font-semibold">{{ $declaration->aspirant ? $declaration->aspirant->first_name . ' ' . $declaration->aspirant->last_name : 'Admin' }}</p>
                                <p class="text-xs opacity-80">{{ $declaration->published_at ? $declaration->published_at->format('F d, Y') : $declaration->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                     </div>
                </div>
            @else
                <div class="p-8 border-b">
                    <h1 class="text-3xl sm:text-4xl font-bold mb-4 text-gray-900">{{ $declaration->title }}</h1>
                    <div class="flex items-center text-gray-600">
                         @if($declaration->aspirant && $declaration->aspirant->photo_path)
                            <img src="{{ Storage::url($declaration->aspirant->photo_path) }}" class="w-10 h-10 rounded-full object-cover mr-3">
                        @endif
                        <div>
                             <span class="font-semibold text-gray-800">{{ $declaration->aspirant ? $declaration->aspirant->first_name . ' ' . $declaration->aspirant->last_name : 'Admin' }}</span>
                             <span class="mx-2">•</span>
                             <span>{{ $declaration->published_at ? $declaration->published_at->format('F d, Y') : $declaration->created_at->format('F d, Y') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-8">
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! $declaration->content !!}
                </div>

                @if(!empty($declaration->media_attachments))
                    <div class="mt-12">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Gallery</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($declaration->media_attachments as $media)
                                <div class="group relative overflow-hidden rounded-lg shadow-md cursor-pointer">
                                    <img src="{{ Storage::url($media) }}" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </article>
    </div>
@endsection
