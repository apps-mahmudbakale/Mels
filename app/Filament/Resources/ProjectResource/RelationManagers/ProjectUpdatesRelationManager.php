<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\ProjectStatus;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectUpdate;

class ProjectUpdatesRelationManager extends RelationManager
{
    protected static string $relationship = 'updates';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Update Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('status')
                            ->options(ProjectStatus::getOptions())
                            ->searchable()
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('completion_percentage')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->columnSpan(1),

                        DatePicker::make('update_date')
                            ->default(now())
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('amount_spent')
                            ->numeric()
                            ->prefix('₦')
                            ->columnSpan(1),

                        TextInput::make('funding_source')
                            ->columnSpan(1),

                        FileUpload::make('image_path')
                            ->image()
                            ->directory('project-updates/images')
                            ->columnSpan(1),

                        FileUpload::make('document_path')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('project-updates/documents')
                            ->columnSpan(1),

                        RichEditor::make('next_steps')
                            ->columnSpanFull(),

                        DatePicker::make('next_update_date')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn (ProjectUpdate $record): string => $record->title),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'primary',
                        'on_hold' => 'warning',
                        'cancelled' => 'danger',
                        'pending' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()),

                TextColumn::make('completion_percentage')
                    ->suffix('%')
                    ->color(fn ($record) => $record->progressColor()),

                TextColumn::make('update_date')
                    ->date()
                    ->sortable(),

                IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified'),

                TextColumn::make('user.name')
                    ->label('Updated By')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ProjectStatus::getOptions()),
                
                SelectFilter::make('is_verified')
                    ->options([
                        '1' => 'Verified',
                        '0' => 'Not Verified',
                    ])
                    ->attribute('is_verified'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Auth::id();
                        return $data;
                    }),
            ])
            ->actions([
                Action::make('verify')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (ProjectUpdate $record) {
                        $record->verify(Auth::user());
                    })
                    ->visible(fn (ProjectUpdate $record) => !$record->is_verified)
                    ->requiresConfirmation()
                    ->color('success'),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('update_date', 'desc');
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with(['user']);
    }
}
