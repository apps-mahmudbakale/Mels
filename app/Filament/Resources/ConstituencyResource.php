<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConstituencyResource\Pages;
use App\Filament\Resources\ConstituencyResource\RelationManagers;
use App\Models\Constituency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConstituencyResource extends Resource
{
    protected static ?string $model = Constituency::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'System Settings';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'federal' => 'Federal (Presidential)',
                        'state' => 'State (Gubernatorial)',
                        'senatorial' => 'Senatorial District',
                        'state_house' => 'State House of Assembly',
                        'lga' => 'LGA (Local)',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),
                Forms\Components\Select::make('state_id')
                    ->label('State')
                    ->options(\App\Models\State::pluck('name', 'id'))
                    ->searchable()
                    ->required(fn (callable $get) => in_array($get('type'), ['state', 'lga']))
                    ->hidden(fn (callable $get) => $get('type') === 'federal'),
                Forms\Components\Select::make('lgas')
                    ->relationship('lgas', 'name')
                    ->multiple()
                    ->preload()
                    ->required()
                    ->hidden(fn (callable $get) => $get('type') === 'federal'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'federal' => 'Federal (Presidential)',
                        'state' => 'State (Gubernatorial)',
                        'senatorial' => 'Senatorial District',
                        'state_house' => 'State House',
                        'lga' => 'LGA (Local)',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'federal' => 'success',
                        'state' => 'primary',
                        'senatorial' => 'warning',
                        'state_house' => 'danger',
                        'lga' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('State')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('lgas_count')
                    ->counts('lgas')
                    ->label('LGAs Count')
                    ->badge(),
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
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'federal' => 'Federal',
                        'state' => 'State',
                        'senatorial' => 'Senatorial',
                        'state_house' => 'State House',
                        'lga' => 'LGA',
                    ]),
                Tables\Filters\SelectFilter::make('state')
                    ->relationship('state', 'name')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListConstituencies::route('/'),
            'create' => Pages\CreateConstituency::route('/create'),
            'edit' => Pages\EditConstituency::route('/{record}/edit'),
        ];
    }
}
