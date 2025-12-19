<?php

namespace App\Filament\Resources\Services\ServiceResource\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected static ?string $title = 'تعديل الخدمة';

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تم حفظ التعديلات بنجاح';
    }
}
