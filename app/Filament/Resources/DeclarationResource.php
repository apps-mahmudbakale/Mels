<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeclarationResource\Pages;
use App\Filament\Resources\DeclarationResource\RelationManagers;
use App\Models\Declaration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeclarationResource extends Resource
{
    protected static ?string $model = Declaration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'People';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Content')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Declaration::class, 'slug', ignoreRecord: true),

                                Forms\Components\Textarea::make('excerpt')
                                    ->rows(2)
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->fileAttachmentsDirectory('declarations/content')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Associations')
                            ->schema([
                                Forms\Components\Select::make('aspirant_id')
                                    ->relationship('aspirant', 'id')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'archived' => 'Archived',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Forms\Components\DateTimePicker::make('published_at'),
                            ]),

                        Forms\Components\Section::make('Media')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->image()
                                    ->directory('declarations/featured')
                                    ->imageEditor(),

                                Forms\Components\FileUpload::make('media_attachments')
                                    ->directory('declarations/attachments')
                                    ->multiple()
                                    ->reorderable()
                                    ->openable(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make()
                    ->schema([
                        \Filament\Infolists\Components\Split::make([
                            \Filament\Infolists\Components\Grid::make(1)
                                ->schema([
                                    \Filament\Infolists\Components\Group::make([
                                        \Filament\Infolists\Components\TextEntry::make('title')
                                            ->size(\Filament\Infolists\Components\TextEntry\TextEntrySize::Large)
                                            ->weight(\Filament\Support\Enums\FontWeight::Bold),
                                        \Filament\Infolists\Components\TextEntry::make('aspirant.first_name')
                                            ->label('Author')
                                            ->formatStateUsing(fn ($record) => $record->aspirant ? "{$record->aspirant->first_name} {$record->aspirant->last_name}" : '-')
                                            ->badge()
                                            ->color('primary'),
                                        \Filament\Infolists\Components\TextEntry::make('published_at')
                                            ->dateTime()
                                            ->icon('heroicon-m-calendar')
                                            ->color('gray'),
                                    ]),
                                    \Filament\Infolists\Components\TextEntry::make('content')
                                        ->prose()
                                        ->markdown()
                                        ->columnSpanFull(),
                                ]),
                            \Filament\Infolists\Components\Section::make('Media Gallery')
                                ->schema([
                                    \Filament\Infolists\Components\ImageEntry::make('featured_image')
                                        ->hiddenLabel()
                                        ->extraImgAttributes(['class' => 'w-full h-auto rounded-lg shadow-md mb-4']),
                                    \Filament\Infolists\Components\ImageEntry::make('media_attachments')
                                        ->label('Gallery')
                                        ->visible(fn ($state) => count($state ?? []) > 0)
                                        ->circular(),
                                ])
                                ->grow(false),
                        ])->from('md'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image'),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('aspirant.first_name')
                    ->label('Aspirant')
                    ->formatStateUsing(fn ($record) => $record->aspirant ? "{$record->aspirant->first_name} {$record->aspirant->last_name}" : '-')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                Tables\Filters\SelectFilter::make('aspirant')
                    ->relationship('aspirant', 'first_name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeclarations::route('/'),
            'create' => Pages\CreateDeclaration::route('/create'),
            'view' => Pages\ViewDeclaration::route('/{record}'),
            'edit' => Pages\EditDeclaration::route('/{record}/edit'),
        ];
    }
}
