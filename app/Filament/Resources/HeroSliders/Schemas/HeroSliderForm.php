<?php

namespace App\Filament\Resources\HeroSliders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HeroSliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('location')
                    ->required()
                    ->default('home'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
