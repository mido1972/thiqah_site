<?php

namespace App\Filament\Resources\Services\ServiceResource\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected static ?string $title = 'الخدمات / الأنظمة';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('إضافة خدمة جديدة'),
        ];
    }
}
