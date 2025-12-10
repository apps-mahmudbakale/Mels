<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AspirantResource\Pages;
use App\Filament\Resources\AspirantResource\RelationManagers;
use App\Models\Aspirant;
use App\Models\Constituency;
use App\Models\Office;
use App\Models\Party;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class AspirantResource extends Resource
{
    protected static ?string $model = Aspirant::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'People';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(20),
                            ]),
                    ]),

                Section::make('Political Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('party_id')
                                    ->relationship('party', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('office_id')
                                    ->relationship('office', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('constituency_id')
                                    ->relationship('constituency', 'name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('state_id')
                                    ->relationship('state', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Section::make('Profile')
                    ->schema([
                        FileUpload::make('photo_path')
                            ->image()
                            ->directory('aspirants/photos')
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('bio')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_incumbent')
                            ->label('Current Office Holder')
                            ->inline(false),
                        Toggle::make('is_active')
                            ->label('Active Candidate')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->compact()
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->full_name).'&color=7F9CF5&background=EBF4FF'),
                
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('party.name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('office.name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('constituency.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('is_incumbent')
                    ->label('Incumbent')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('party')
                    ->relationship('party', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('office')
                    ->relationship('office', 'name')
                    ->searchable()
                    ->preload(),
                
                TernaryFilter::make('is_incumbent')
                    ->label('Incumbent Only'),
                
                TernaryFilter::make('is_active')
                    ->label('Active Only'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            RelationManagers\ProjectsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAspirants::route('/'),
            'create' => Pages\CreateAspirant::route('/create'),
            'view' => Pages\ViewAspirant::route('/{record}'),
            'edit' => Pages\EditAspirant::route('/{record}/edit'),
        ];
    }
}
