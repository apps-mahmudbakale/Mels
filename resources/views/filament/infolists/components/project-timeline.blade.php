<div class="space-y-6">
    @foreach($updates as $update)
        <div class="relative pl-8 pb-6 border-l-2 border-gray-200 dark:border-gray-700 group">
            <!-- Dot -->
            <div class="absolute -left-2.5 flex h-5 w-5 items-center justify-center rounded-full bg-primary-500 text-white ring-4 ring-white dark:ring-gray-900">
                @switch($update->status)
                    @case('completed')
                        <x-heroicon-s-check class="h-3.5 w-3.5" />
                        @break
                    @case('in_progress')
                        <x-heroicon-s-arrow-path class="h-3.5 w-3.5 animate-spin" />
                        @break
                    @case('on_hold')
                        <x-heroicon-s-pause class="h-3.5 w-3.5" />
                        @break
                    @case('cancelled')
                        <x-heroicon-s-x-mark class="h-3.5 w-3.5" />
                        @break
                    @default
                        <x-heroicon-s-ellipsis-horizontal class="h-3.5 w-3.5" />
                @endswitch
            </div>
            
            <!-- Content -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $update->title }}
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $update->update_date->format('M d, Y') }}
                    </span>
                </div>
                
                <div class="prose prose-sm max-w-none dark:prose-invert">
                    {!! \Illuminate\Support\Str::markdown($update->description) !!}
                </div>
                
                @if($update->completion_percentage > 0)
                    <div class="mt-2">
                        <div class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <span>Progress</span>
                            <span>{{ $update->completion_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-{{ $update->progressColor() }}-500 h-2.5 rounded-full" 
                                 style="width: {{ $update->completion_percentage }}%">
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($update->image_path)
                    <div class="mt-2">
                        <img src="{{ Storage::url($update->image_path) }}" 
                             alt="Update image" 
                             class="h-32 w-auto rounded-md object-cover">
                    </div>
                @endif
                
                @if($update->next_steps)
                    <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-md">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Next Steps</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $update->next_steps }}
                        </p>
                        @if($update->next_update_date)
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Next update: {{ $update->next_update_date->format('M d, Y') }}
                            </p>
                        @endif
                    </div>
                @endif
                
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-1">
                    <span>
                        @if($update->user)
                            Updated by {{ $update->user->name }}
                        @endif
                    </span>
                    @if($update->is_verified)
                        <span class="inline-flex items-center text-green-500">
                            <x-heroicon-s-check-circle class="h-4 w-4 mr-1" />
                            Verified
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    
    @if($updates->isEmpty())
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <x-heroicon-s-document-text class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium">No updates yet</h3>
            <p class="mt-1 text-sm">Add the first update to this project.</p>
        </div>
    @endif
</div>
