<div class="space-y-4">
    @if($updates->isEmpty())
        <div class="rounded-lg border border-dashed border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">No updates available for this project yet.</p>
        </div>
    @else
        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @foreach($updates as $update)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                            @endif
                            
                            <div class="relative flex space-x-3">
                                <div>
                                    @php
                                        $iconColor = match($update->status) {
                                            'completed' => 'bg-green-500',
                                            'in_progress' => 'bg-blue-500',
                                            'on_hold' => 'bg-yellow-500',
                                            'abandoned' => 'bg-red-500',
                                            default => 'bg-gray-400',
                                        };
                                        
                                        $icon = match($update->status) {
                                            'completed' => 'heroicon-s-check-circle',
                                            'in_progress' => 'heroicon-s-arrow-path',
                                            'on_hold' => 'heroicon-s-pause-circle',
                                            'abandoned' => 'heroicon-s-x-circle',
                                            default => 'heroicon-s-information-circle',
                                        };
                                    @endphp
                                    
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full {{ $iconColor }} ring-8 ring-white dark:ring-gray-900">
                                        <x-dynamic-component :component="$icon" class="h-5 w-5 text-white" />
                                    </span>
                                </div>
                                
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $update->updater?->name ?? 'System' }}</span>
                                            updated the project status to 
                                            <span class="font-medium">{{ str($update->status)->replace('_', ' ')->title() }}</span>
                                        </p>
                                        
                                        @if($update->description)
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400 prose dark:prose-invert">
                                                {!! \Illuminate\Support\Str::markdown($update->description) !!}
                                            </div>
                                        @endif
                                        
                                        @if($update->image_path || $update->document_path)
                                            <div class="mt-2 flex space-x-2">
                                                @if($update->image_path)
                                                    <a href="{{ Storage::url($update->image_path) }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        View Image
                                                    </a>
                                                @endif
                                                
                                                @if($update->document_path)
                                                    <a href="{{ Storage::url($update->document_path) }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        View Document
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                        <time datetime="{{ $update->update_date->toIso8601String() }}">
                                            {{ $update->update_date->diffForHumans() }}
                                        </time>
                                        
                                        @if($update->completion_percentage)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $update->project->progressColor() }}-100 text-{{ $update->project->progressColor() }}-800 dark:bg-{{ $update->project->progressColor() }}-900 dark:text-{{ $update->project->progressColor() }}-200">
                                                    {{ $update->completion_percentage }}% Complete
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
