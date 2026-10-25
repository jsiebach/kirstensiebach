<?php

namespace App\Filament\Resources\ResourceSections\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class ResourceSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components(static::fields());
    }

    /**
     * Also used to create a section straight from the resource form.
     *
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->helperText('The heading shown on the Resources page.'),
            Textarea::make('description')
                ->rows(2)
                ->helperText('Optional. Shown under the heading.'),
        ];
    }
}
