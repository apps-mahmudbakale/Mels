<?php

namespace App\Filament\Resources\AspirantResource\Pages;

use App\Filament\Resources\AspirantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;

class ViewAspirant extends ViewRecord
{
    protected static string $resource = AspirantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        ImageEntry::make('photo_path')
                            ->label('Photo')
                            ->circular()
                            ->size(120)
                            ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->full_name).'&color=7F9CF5&background=EBF4FF')
                            ->visible(fn ($record) => !empty($record->photo_path)),
                        
                        TextEntry::make('full_name')
                            ->label('Full Name')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        
                        TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-m-envelope')
                            ->copyable(),
                        
                        TextEntry::make('phone')
                            ->label('Phone')
                            ->icon('heroicon-m-phone')
                            ->copyable()
                            ->visible(fn ($record) => !empty($record->phone)),
                    ])
                    ->columns(2)
                    ->icon('heroicon-o-user'),

                Section::make('Political Information')
                    ->schema([
                        TextEntry::make('party.name')
                            ->label('Party')
                            ->badge()
                            ->color('primary'),
                        
                        TextEntry::make('office.name')
                            ->label('Office')
                            ->badge()
                            ->color('success'),
                        
                        TextEntry::make('constituency.name')
                            ->label('Constituency')
                            ->badge()
                            ->color('info')
                            ->visible(fn ($record) => !empty($record->constituency_id)),
                        
                        TextEntry::make('state.name')
                            ->label('State')
                            ->badge()
                            ->color('warning')
                            ->visible(fn ($record) => !empty($record->state_id)),
                    ])
                    ->columns(2)
                    ->icon('heroicon-o-building-library'),

                Section::make('Biography')
                    ->schema([
                        TextEntry::make('bio')
                            ->label('')
                            ->html()
                            ->prose()
                            ->columnSpanFull()
                            ->placeholder('No biography available'),
                    ])
                    ->icon('heroicon-o-document-text')
                    ->description('About the aspirant'),

                Section::make('Status')
                    ->schema([
                        IconEntry::make('is_incumbent')
                            ->label('Current Office Holder')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('gray'),
                        
                        IconEntry::make('is_active')
                            ->label('Active Candidate')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('gray'),
                    ])
                    ->columns(2)
                    ->icon('heroicon-o-information-circle'),
            ]);
    }
}
