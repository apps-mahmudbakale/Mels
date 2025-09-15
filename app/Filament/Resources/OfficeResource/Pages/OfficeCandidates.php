<?php

namespace App\Filament\Resources\OfficeResource\Pages;

use App\Filament\Resources\OfficeResource;
use App\Models\Office;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OfficeCandidates extends Page implements \Filament\Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    
    protected static string $resource = OfficeResource::class;
    protected static string $view = 'filament.resources.office-resource.pages.office-candidates';
    
    public Office $record;
    
    public function mount(Office $record): void
    {
        $this->record = $record;
    }
    
    public function getTitle(): string
    {
        return "{$this->record->name} - Candidates";
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query($this->record->aspirants()->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('party.name')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('constituency.name')
                    ->label('Constituency')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_incumbent')
                    ->label('Incumbent?')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('party')
                    ->relationship('party', 'name')
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('is_incumbent')
                    ->label('Incumbent')
                    ->placeholder('All')
                    ->trueLabel('Incumbents only')
                    ->falseLabel('Non-incumbents only'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => \App\Filament\Resources\AspirantResource::getUrl('edit', ['record' => $record])),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(\App\Filament\Resources\AspirantResource::getUrl('create', ['office_id' => $this->record->id])),
            ]);
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            OfficeResource\Widgets\OfficeOverview::class,
        ];
    }
}
