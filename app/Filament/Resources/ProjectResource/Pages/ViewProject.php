<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\ComponentContainer;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\HtmlString;
use App\Filament\Infolists\Components\ProjectTimeline;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.projects.view';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Grid::make(3)->schema([
                    InfoSection::make('Project Details')
                        ->schema([
                            TextEntry::make('title')
                                ->size(TextEntry\TextEntrySize::Large)
                                ->weight('bold')
                                ->columnSpanFull(),

                            TextEntry::make('aspirant.full_name')
                                ->label('Aspirant')
                                ->url(fn (Project $record) => route('filament.admin.resources.aspirants.view', $record->aspirant))
                                ->icon('heroicon-o-user-circle')
                                ->color('primary'),

                            TextEntry::make('description')
                                ->markdown()
                                ->columnSpanFull(),

                            TextEntry::make('category')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'infrastructure' => 'info',
                                    'education' => 'success',
                                    'health' => 'danger',
                                    'agriculture' => 'warning',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()),

                            TextEntry::make('priority')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'high' => 'danger',
                                    'medium' => 'warning',
                                    'low' => 'success',
                                    'critical' => 'danger',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => str($state)->title()),

                            TextEntry::make('document_path')
                                ->label('Document')
                                ->url(fn ($state) => $state ? Storage::url($state) : null)
                                ->openUrlInNewTab()
                                ->hidden(fn ($state) => !$state)
                                ->icon('heroicon-o-document-text'),

                            TextEntry::make('location')
                                ->icon('heroicon-o-map-pin'),

                            TextEntry::make('beneficiaries')
                                ->icon('heroicon-o-user-group')
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->columnSpan(2),

                    InfoSection::make('Project Media')
                        ->schema([
                            ImageEntry::make('image_path')
                                ->label('')
                                ->height(200)
                                ->hidden(fn ($state) => !$state),

                            ViewEntry::make('progress')
                                ->view('filament.infolists.components.project-progress', [
                                    'record' => $this->record,
                                ])
                                ->hidden(fn ($record) => !$record->completion_percentage),

                            TextEntry::make('status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'completed' => 'success',
                                    'in_progress' => 'primary',
                                    'on_hold' => 'warning',
                                    'abandoned' => 'danger',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()),

                            TextEntry::make('completion_percentage')
                                ->label('Completion')
                                ->suffix('%')
                                ->color(fn ($record) => $record->progressColor()),

                            TextEntry::make('promise_date')
                                ->date(),

                            TextEntry::make('start_date')
                                ->date()
                                ->placeholder('Not started')
                                ->hidden(fn ($state) => !$state),

                            TextEntry::make('expected_completion_date')
                                ->date()
                                ->placeholder('Not set')
                                ->hidden(fn ($state) => !$state),

                            TextEntry::make('actual_completion_date')
                                ->date()
                                ->placeholder('Not completed')
                                ->hidden(fn ($state) => !$state),
                        ])
                        ->columnSpan(1),
                ]),

                InfoSection::make('Project Timeline')
                    ->schema([
                        ProjectTimeline::make('updates')
                    ])
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
