<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectUpdateResource\Pages;
use App\Filament\Resources\ProjectUpdateResource\RelationManagers;
use App\Enums\ProjectStatus;
use App\Models\ProjectUpdate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectUpdateResource extends Resource
{
    protected static ?string $model = ProjectUpdate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Projects';
    protected static ?string $navigationLabel = 'Project Updates';
    protected static ?string $modelLabel = 'Project Update';
    protected static ?string $navigationBadgeColor = 'info';
    
    public static function getNavigationGroup(): string
    {
        return 'Projects';
    }
    
    public static function getNavigationSort(): int
    {
        return 5;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'title', modifyQueryUsing: fn (Builder $query) => $query->with('aspirant'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->title} (" . ($record->aspirant?->full_name ?? 'No Aspirant') . ")")
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options(ProjectStatus::getOptions())
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('completion_percentage')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->directory('project-updates/images')
                    ->imageEditor(),
                Forms\Components\FileUpload::make('document_path')
                    ->label('Documents')
                    ->directory('project-updates/documents')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ])
                    ->downloadable(),
                Forms\Components\TextInput::make('amount_spent')
                    ->numeric(),
                Forms\Components\TextInput::make('funding_source')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('update_date')
                    ->required(),
                Forms\Components\Textarea::make('next_steps')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('next_update_date'),
                Forms\Components\Toggle::make('is_verified')
                    ->required(),
                Forms\Components\DateTimePicker::make('verified_at'),
                Forms\Components\Select::make('verified_by')
                    ->relationship('verifier', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('completion_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path'),
                Tables\Columns\TextColumn::make('document_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_spent')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('funding_source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('update_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('next_update_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('verifier.name')
                    ->label('Verified by')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListProjectUpdates::route('/'),
            'create' => Pages\CreateProjectUpdate::route('/create'),
            'edit' => Pages\EditProjectUpdate::route('/{record}/edit'),
        ];
    }
}
