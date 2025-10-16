<?php

namespace App\Filament\Resources\Publications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PublicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page.title')
                    ->label('Page')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('publication_name')
                    ->label('Journal/Conference')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('date_published')
                    ->date()
                    ->sortable()
                    ->label('Published'),
                IconColumn::make('published')
                    ->boolean()
                    ->label('Status'),
                TextColumn::make('doi')
                    ->searchable()
                    ->toggleable(),
            ])
            ->defaultSort('date_published', 'desc')
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
