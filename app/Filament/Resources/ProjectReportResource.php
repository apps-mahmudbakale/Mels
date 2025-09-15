<?php

namespace App\Filament\Resources;

use App\Exports\ProjectsExport;
use App\Filament\Resources\ProjectReportResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;

class ProjectReportResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Project Reports';
    protected static ?string $modelLabel = 'Project Report';
    protected static ?string $navigationParentItem = 'Reports';
    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'project-reports';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form fields for the report criteria
                Select::make('aspirant_id')
                    ->relationship('aspirant', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name)
                    ->searchable()
                    ->preload(),

                Select::make('lga')
                    ->options(function () {
                        return \App\Models\LGA::pluck('name', 'name');
                    })
                    ->searchable(),
                Select::make('office_id')
                    ->relationship('office', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('party')
                    ->options([
                        'PDP' => 'PDP',
                        'APC' => 'APC',
                        'LP' => 'Labour Party',
                        'NNPP' => 'NNPP',
                    ]),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aspirant.first_name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->aspirant?->full_name)
                    ->label('Aspirant'),
                Tables\Columns\TextColumn::make('lga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('office.name')
                    ->label('Office')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'primary',
                        'on_hold' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()),
                Tables\Columns\TextColumn::make('completion_percentage')
                    ->suffix('%')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('aspirant')
                    ->relationship('aspirant', 'full_name'),
                Tables\Filters\SelectFilter::make('lga')
                    ->options(fn () => \App\Models\LGA::pluck('name', 'name')),
                Tables\Filters\SelectFilter::make('office')
                    ->relationship('office', 'name'),
                Tables\Filters\SelectFilter::make('party')
                    ->options([
                        'PDP' => 'PDP',
                        'APC' => 'APC',
                        'LP' => 'Labour Party',
                        'NNPP' => 'NNPP',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'on_hold' => 'On Hold',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('export')
                    ->label('Export to Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (array $records, array $data) {
                        $filters = request()->all();

                        // Clean up filters
                        unset($filters['tableFilters']);
                        unset($filters['tableSortColumn']);
                        unset($filters['tableSortDirection']);
                        unset($filters['tableSearchQuery']);
                        unset($filters['tableColumnSearchQueries']);

                        $format = $data['format'] ?? 'xlsx';
                        $filename = 'projects-report-' . now()->format('Y-m-d') . '.' . $format;

                        return (new \App\Exports\ProjectsExport($filters))->download($filename, \Maatwebsite\Excel\Excel::XLSX);
                    })
                    ->form([
                        Select::make('format')
                            ->label('Export Format')
                            ->options([
                                'xlsx' => 'Excel (XLSX)',
                                'csv' => 'CSV',
                            ])
                            ->default('xlsx'),
                    ])
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation()
                    ->modalHeading('Export Projects')
                    ->modalDescription('Select the format and click Export to download the report.')
                    ->modalButton('Export'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add any relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectReports::route('/'),
        ];
    }
}
