<?php

namespace App\Filament\Resources\Pages\OutreachPages\Schemas;

use App\Rules\YouTubeUrl;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OutreachPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ]),

                Section::make('SEO Settings')
                    ->schema([
                        TextInput::make('meta_title')
                            ->required()
                            ->maxLength(255)
                            ->label('Meta Title'),
                        Textarea::make('meta_description')
                            ->maxLength(500)
                            ->rows(3)
                            ->label('Meta Description'),
                    ])
                    ->collapsible(),

                Section::make('Introduction')
                    ->columnSpanFull()
                    ->schema([
                        MarkdownEditor::make('content.intro')
                            ->label('Introduction'),
                        MarkdownEditor::make('content.contact_callout')
                            ->label('Contact Box')
                            ->helperText('Shown in the blue box under the introduction.'),
                        FileUpload::make('content.intro_image')
                            ->image()
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('2000')
                            ->automaticallyResizeImagesToHeight('2000')
                            ->acceptedFileTypes(['image/*'])
                            ->disk('public')
                            ->directory('outreach')
                            ->label('Introduction Photo'),
                    ]),

                Section::make('Videos')
                    ->columnSpanFull()
                    ->description('Shown on the page in this order. Use the arrows to reorder.')
                    ->schema([
                        Repeater::make('content.videos')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('youtube_url')
                                    ->label('YouTube Link')
                                    ->required()
                                    ->url()
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->rule(new YouTubeUrl),
                                Textarea::make('description')
                                    ->rows(2),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Add video')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),

                Section::make('Photo Slideshow')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('content.photos')
                            ->hiddenLabel()
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('2000')
                            ->automaticallyResizeImagesToHeight('2000')
                            ->acceptedFileTypes(['image/*'])
                            ->disk('public')
                            ->directory('outreach')
                            ->panelLayout('grid'),
                    ])
                    ->collapsible(),

                Section::make('Talks & Experience')
                    ->columnSpanFull()
                    ->description('Each list becomes a table on the page, e.g. "Recent Keynote Talks" or "Teaching Experience".')
                    ->schema([
                        Repeater::make('content.sections')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('heading')
                                    ->required()
                                    ->maxLength(255),
                                Repeater::make('entries')
                                    ->schema([
                                        TextInput::make('date')
                                            ->placeholder('4/11/2019')
                                            ->maxLength(50),
                                        Textarea::make('description')
                                            ->required()
                                            ->rows(2)
                                            ->columnSpan(2),
                                        TextInput::make('link')
                                            ->url(),
                                    ])
                                    ->columns(4)
                                    ->itemLabel(fn (array $state): ?string => trim(($state['date'] ?? '').' '.($state['description'] ?? '')) ?: null)
                                    ->addActionLabel('Add entry')
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->collapsed(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? null)
                            ->addActionLabel('Add list')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->collapsed()
                            ->defaultItems(0),
                    ]),
            ]);
    }
}
