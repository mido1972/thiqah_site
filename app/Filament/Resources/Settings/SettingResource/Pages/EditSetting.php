<?php

namespace App\Filament\Resources\Settings\SettingResource\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected static ?string $title = 'تعديل الإعدادات';

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تم حفظ الإعدادات بنجاح';
    }
}
