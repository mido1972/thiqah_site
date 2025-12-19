<?php

namespace App\Filament\Resources\Settings\SettingResource\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected static ?string $title = 'إعدادات الموقع';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('إضافة إعدادات'),
        ];
    }
}
