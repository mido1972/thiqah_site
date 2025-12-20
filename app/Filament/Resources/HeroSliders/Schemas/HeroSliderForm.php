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
                    ->label('Name')
                    ->required()
                    ->maxLength(190),

                TextInput::make('location')
                    ->label('Location')
                    ->required()
                    ->default('home')
                    ->maxLength(50)
                    ->helperText('مثال: home / services / about'),

                // ✅ NEW: autoplay interval from admin
                TextInput::make('autoplay_interval_ms')
                    ->label('مدة التبديل (ms)')
                    ->helperText('مثال: 7000 = 7 ثواني (الحد الأدنى 1000 والحد الأقصى 60000)')
                    ->numeric()
                    ->minValue(1000)
                    ->maxValue(60000)
                    ->default(7000)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Is active')
                    ->required()
                    ->default(true),
            ]);
    }
}
