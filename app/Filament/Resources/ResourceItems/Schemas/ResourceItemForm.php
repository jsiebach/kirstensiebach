<?php

namespace App\Filament\Resources\ResourceItems\Schemas;

use App\Enums\ResourceType;
use App\Filament\Resources\ResourceSections\Schemas\ResourceSectionForm;
use App\Rules\YouTubeUrl;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ResourceItemForm
{
    /**
     * Office documents, PDFs and zips teachers might download.
     *
     * @var array<int, string>
     */
    public const DOCUMENT_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
        'application/x-zip-compressed',
        'text/plain',
        'text/csv',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                ToggleButtons::make('type')
                    ->options(ResourceType::class)
                    ->default(ResourceType::Document)
                    ->inline()
                    ->required()
                    ->live(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->rows(3),
                Select::make('resource_section_id')
                    ->label('Section')
                    ->relationship('section', 'name', fn ($query) => $query->ordered())
                    ->createOptionForm(ResourceSectionForm::fields())
                    ->preload()
                    ->placeholder('None (top of the page)')
                    ->helperText('The heading this resource appears under. Use + to add a new section.'),
                FileUpload::make('file_path')
                    ->label(fn (Get $get): string => static::typeOf($get) === ResourceType::Image ? 'Picture' : 'File')
                    ->visible(fn (Get $get): bool => (bool) static::typeOf($get)?->hasFile())
                    ->required(fn (Get $get): bool => (bool) static::typeOf($get)?->hasFile())
                    ->acceptedFileTypes(fn (Get $get): array => static::typeOf($get) === ResourceType::Image ? ['image/*'] : self::DOCUMENT_TYPES)
                    ->automaticallyResizeImagesMode('contain')
                    ->automaticallyResizeImagesToWidth('2000')
                    ->automaticallyResizeImagesToHeight('2000')
                    ->maxSize(40 * 1024)
                    ->disk('public')
                    ->directory('resources')
                    ->storeFileNamesIn('file_name')
                    ->downloadable()
                    ->helperText('Up to 40 MB.'),
                TextInput::make('url')
                    ->label(fn (Get $get): string => static::typeOf($get) === ResourceType::Video ? 'YouTube Link' : 'Link')
                    ->visible(fn (Get $get): bool => static::typeOf($get)?->hasFile() === false)
                    ->required(fn (Get $get): bool => static::typeOf($get)?->hasFile() === false)
                    ->url()
                    ->maxLength(2048)
                    ->rule(fn (Get $get): ?YouTubeUrl => static::typeOf($get) === ResourceType::Video ? new YouTubeUrl : null)
                    ->helperText(fn (Get $get): ?string => static::typeOf($get) === ResourceType::Video ? 'Upload the video to YouTube (unlisted is fine) and paste its link here.' : null),
            ]);
    }

    private static function typeOf(Get $get): ?ResourceType
    {
        $type = $get('type');

        return $type instanceof ResourceType ? $type : ResourceType::tryFrom((string) $type);
    }
}
