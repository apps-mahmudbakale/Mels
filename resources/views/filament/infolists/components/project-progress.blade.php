<div class="space-y-2">
    <div class="flex justify-between text-sm font-medium">
        <span>Project Progress</span>
        <span class="text-{{ $record->progressColor() }}-600 dark:text-{{ $record->progressColor() }}-400">
            {{ $record->completion_percentage }}%
        </span>
    </div>
    <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
        <div 
            class="h-full rounded-full bg-{{ $record->progressColor() }}-500"
            style="width: {{ $record->completion_percentage }}%"
        ></div>
    </div>
    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
        <span>0%</span>
        <span>100%</span>
    </div>
    
    @if($record->isDelayed() && $record->status !== 'completed')
        <div class="mt-2 text-sm text-amber-600 dark:text-amber-400">
            <span class="flex items-center">
                <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Project is delayed
            </span>
        </div>
    @endif
</div>
