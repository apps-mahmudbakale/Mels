<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficeResource\Pages;
use App\Filament\Resources\OfficeResource\RelationManagers;
use App\Models\Office;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'System Settings';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Office Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Office Name')
                            ->placeholder('e.g., Governor, Senator, House of Representatives'),
                        
                        Forms\Components\Select::make('level')
                            ->options([
                                'federal' => 'Federal',
                                'state' => 'State',
                                'local' => 'Local',
                            ])
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->options([
                                'executive' => 'Executive',
                                'legislative' => 'Legislative',
                            ])
                            ->required()
                            ->default('executive'),

                        Forms\Components\Select::make('constituency_id')
                            ->relationship('constituency', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Constituency')
                            ->placeholder('Select constituency (optional)'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->rows(3)
                            ->placeholder('Brief description of the office and its responsibilities'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'federal' => 'success',
                        'state' => 'warning',
                        'local' => 'gray',
                    }),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'executive' => 'primary',
                        'legislative' => 'info',
                    }),
                
                Tables\Columns\TextColumn::make('constituency.name')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->placeholder('N/A'),
                
                Tables\Columns\TextColumn::make('constituency.region.name')
                    ->label('Region')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('warning')
                    ->placeholder('N/A'),
                
                Tables\Columns\TextColumn::make('aspirants_count')
                    ->counts('aspirants')
                    ->label('Aspirants')
                    ->badge()
                    ->color('success'),
                
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
                Tables\Filters\SelectFilter::make('constituency')
                    ->relationship('constituency', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\AspirantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOffices::route('/'),
            'create' => Pages\CreateOffice::route('/create'),
            'edit' => Pages\EditOffice::route('/{record}/edit'),
            'candidates' => Pages\OfficeCandidates::route('/{record}/candidates'),
        ];
    }
}
