<?php

namespace App\Filament\Resources\AspirantResource\RelationManagers;

use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Project Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('category')
                            ->options([
                                'infrastructure' => 'Infrastructure',
                                'education' => 'Education',
                                'health' => 'Health',
                                'agriculture' => 'Agriculture',
                                'security' => 'Security',
                                'employment' => 'Employment',
                                'youth_development' => 'Youth Development',
                                'women_empowerment' => 'Women Empowerment',
                                'others' => 'Others',
                            ])
                            ->required()
                            ->columnSpan(1),

                        Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                                'critical' => 'Critical',
                            ])
                            ->default('medium')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('estimated_cost')
                            ->numeric()
                            ->prefix('₦')
                            ->columnSpan(1),

                        TextInput::make('location')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('beneficiaries')
                            ->columnSpanFull(),

                        DatePicker::make('promise_date')
                            ->required()
                            ->columnSpan(1),

                        DatePicker::make('start_date')
                            ->columnSpan(1),

                        DatePicker::make('expected_completion_date')
                            ->columnSpan(1),

                        DatePicker::make('actual_completion_date')
                            ->columnSpan(1),

                        Select::make('status')
                            ->options([
                                'not_started' => 'Not Started',
                                'in_progress' => 'In Progress',
                                'on_hold' => 'On Hold',
                                'completed' => 'Completed',
                                'abandoned' => 'Abandoned',
                            ])
                            ->default('not_started')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('completion_percentage')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->columnSpan(1),

                        FileUpload::make('image_path')
                            ->image()
                            ->directory('projects/images')
                            ->columnSpan(1),

                        FileUpload::make('document_path')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('projects/documents')
                            ->columnSpan(1),

                        RichEditor::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn (Project $record): string => $record->title),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'infrastructure' => 'info',
                        'education' => 'success',
                        'health' => 'danger',
                        'agriculture' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'primary',
                        'on_hold' => 'warning',
                        'abandoned' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()),

                Tables\Columns\TextColumn::make('completion_percentage')
                    ->suffix('%')
                    ->color(fn ($record) => $record->progressColor()),

                Tables\Columns\TextColumn::make('promise_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expected_completion_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_public')
                    ->boolean()
                    ->label('Public')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'not_started' => 'Not Started',
                        'in_progress' => 'In Progress',
                        'on_hold' => 'On Hold',
                        'completed' => 'Completed',
                        'abandoned' => 'Abandoned',
                    ]),
                
                SelectFilter::make('category')
                    ->options([
                        'infrastructure' => 'Infrastructure',
                        'education' => 'Education',
                        'health' => 'Health',
                        'agriculture' => 'Agriculture',
                        'security' => 'Security',
                        'employment' => 'Employment',
                        'youth_development' => 'Youth Development',
                        'women_empowerment' => 'Women Empowerment',
                        'others' => 'Others',
                    ]),
                
                SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'critical' => 'Critical',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['aspirant_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('updates')
                    ->url(fn (Project $record): string => route('filament.admin.resources.projects.edit', $record))
                    ->icon('heroicon-o-document-chart-bar')
                    ->label('')
                    ->tooltip('Manage Updates'),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('promise_date', 'desc');
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with('updates')
            ->withoutGlobalScopes();
    }
}
