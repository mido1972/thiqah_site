<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title_ar')
                    ->required(),
                TextInput::make('title_en')
                    ->required(),
                Textarea::make('content_ar')
                    ->columnSpanFull(),
                Textarea::make('content_en')
                    ->columnSpanFull(),
                TextInput::make('meta_title_ar'),
                TextInput::make('meta_title_en'),
                Textarea::make('meta_description_ar')
                    ->columnSpanFull(),
                Textarea::make('meta_description_en')
                    ->columnSpanFull(),
            ]);
    }
}
