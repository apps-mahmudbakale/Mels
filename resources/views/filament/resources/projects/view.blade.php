<x-filament-panels::page>
    {{-- Project Details Section --}}
    <div class="space-y-6">
        {{-- Header with Title and Actions --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">
                    {{ $this->record->title }}
                </h2>
                <p class="text-gray-500 dark:text-gray-400">
                    {{ $this->record->aspirant->full_name }}
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                @foreach($this->getHeaderActions() as $action)
                    {{ $action }}
                @endforeach
            </div>
        </div>

        {{-- Main Content --}}
        <div class="space-y-6">
            {{-- Project Information --}}
            <div class="grid gap-6 md:grid-cols-3">
                {{-- Left Column --}}
                <div class="space-y-6 md:col-span-2">
                    {{-- Project Description --}}
                    <x-filament::section>
                        <x-slot name="heading">
                            <x-filament::section.heading>
                                Project Details
                            </x-filament::section.heading>
                        </x-slot>
                        
                        <div class="prose dark:prose-invert max-w-none">
                            {!! \Illuminate\Support\Str::markdown($this->record->description) !!}
                        </div>
                    </x-filament::section>

                    {{-- Project Timeline --}}
                    <x-filament::section>
                        <x-slot name="heading">
                            <x-filament::section.heading>
                                Project Timeline
                            </x-filament::section.heading>
                        </x-slot>

                        @if($this->record->updates->count() > 0)
                            @include('filament.infolists.components.project-timeline', [
                                'updates' => $this->record->updates->sortByDesc('update_date')
                            ])
                        @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <p>No updates available yet.</p>
                            </div>
                        @endif
                    </x-filament::section>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    {{-- Project Status --}}
                    <x-filament::section>
                        <x-slot name="heading">
                            <x-filament::section.heading>
                                Project Status
                            </x-filament::section.heading>
                        </x-slot>

                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress</span>
                                    <span class="text-sm font-medium">{{ $this->record->completion_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-primary-500 h-2.5 rounded-full" 
                                         style="width: {{ $this->record->completion_percentage }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <p class="font-medium">
                                        <x-filament::badge :color="match($this->record->status) {
                                            'completed' => 'success',
                                            'in_progress' => 'primary',
                                            'on_hold' => 'warning',
                                            'cancelled' => 'danger',
                                            default => 'gray',
                                        }">
                                            {{ str($this->record->status)->replace('_', ' ')->title() }}
                                        </x-filament::badge>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Priority</p>
                                    <p class="font-medium">
                                        <x-filament::badge :color="match($this->record->priority) {
                                            'high' => 'danger',
                                            'medium' => 'warning',
                                            'low' => 'success',
                                            default => 'gray',
                                        }">
                                            {{ str($this->record->priority)->title() }}
                                        </x-filament::badge>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                                    <p class="font-medium">
                                        {{ str($this->record->category)->replace('_', ' ')->title() }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Location</p>
                                    <p class="font-medium">{{ $this->record->location }}</p>
                                </div>
                            </div>
                        </div>
                    </x-filament::section>

                    {{-- Project Media --}}
                    @if($this->record->image_path)
                        <x-filament::section>
                            <x-slot name="heading">
                                <x-filament::section.heading>
                                    Project Image
                                </x-filament::section.heading>
                            </x-slot>

                            <div class="rounded-lg overflow-hidden">
                                <img 
                                    src="{{ asset('storage/' . $this->record->image_path) }}" 
                                    alt="Project Image"
                                    class="w-full h-auto rounded-lg"
                                    onerror="this.style.display='none'"
                                >
                            </div>
                        </x-filament::section>
                    @endif

                    {{-- Project Documents --}}
                    @if($this->record->document_path)
                        <x-filament::section>
                            <x-slot name="heading">
                                <x-filament::section.heading>
                                    Project Document
                                </x-filament::section.heading>
                            </x-slot>

                            <a 
                                href="{{ asset('storage/' . $this->record->document_path) }}" 
                                target="_blank"
                                class="inline-flex items-center text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300"
                            >
                                <x-heroicon-o-document-text class="w-5 h-5 mr-2" />
                                View Document
                            </a>
                        </x-filament::section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
