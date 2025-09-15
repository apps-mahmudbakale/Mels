<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Enums\ProjectCategory;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use App\Models\Aspirant;
use App\Models\Project;
use Filament\Forms\Components\Grid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Projects';
    protected static ?string $navigationLabel = 'Projects';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Projects';
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        Select::make('aspirant_id')
                            ->label('Aspirant')
                            ->relationship(
                                name: 'aspirant',
                                titleAttribute: 'full_name',
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('first_name')->orderBy('last_name')
                            )
                            ->getOptionLabelFromRecordUsing(fn (\App\Models\Aspirant $record) => $record->full_name)
                            ->searchable(['first_name', 'last_name'])
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('category')
                            ->options(ProjectCategory::getOptions())
                            ->required()
                            ->searchable(),

                        Select::make('priority')
                            ->options(ProjectPriority::getOptions())
                            ->required()
                            ->searchable(),

                        TextInput::make('estimated_cost')
                            ->numeric()
                            ->prefix('₦')
                            ->minValue(0),

                        TextInput::make('location')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('beneficiaries')
                            ->label('Beneficiaries')
                            ->placeholder('e.g., Youth, Women, Farmers')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Project Timeline')
                    ->schema([
                        DatePicker::make('promise_date')
                            ->required(),

                        DatePicker::make('start_date'),

                        DatePicker::make('expected_completion_date'),

                        DatePicker::make('actual_completion_date'),
                    ])
                    ->columns(2),

                Section::make('Status & Media')
                    ->schema([
                        Select::make('status')
                            ->options(ProjectStatus::getOptions())
                            ->required()
                            ->searchable(),

                        TextInput::make('completion_percentage')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->default(0),

                        FileUpload::make('image_path')
                            ->image()
                            ->directory('project-images')
                            ->imageEditor(),

                        FileUpload::make('document_path')
                            ->label('Documents')
                            ->directory('project-documents')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ])
                            ->downloadable(),

                        Toggle::make('is_public')
                            ->label('Make project public')
                            ->default(true),

                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aspirant.full_name')
                    ->label('Aspirant')
                    ->sortable()
                    ->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('estimated_cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('beneficiaries')
                    ->searchable(),
                Tables\Columns\TextColumn::make('promise_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_completion_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_completion_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('completion_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path'),
                Tables\Columns\TextColumn::make('document_path')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
