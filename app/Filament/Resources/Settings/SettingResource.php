<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\SettingResource\Pages;
use App\Models\Setting;

use BackedEnum;
use UnitEnum;

use Filament\Resources\Resource;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;

use Illuminate\Support\Facades\Storage;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static UnitEnum|string|null $navigationGroup = 'الإعدادات العامة';
    protected static ?string $navigationLabel = 'إعدادات الموقع';
    protected static ?string $pluralModelLabel = 'إعدادات الشركة';
    protected static ?string $modelLabel = 'إعدادات';
    protected static ?string $recordTitleAttribute = 'company_name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            Section::make('بيانات الشركة الأساسية')->schema([
                TextInput::make('company_name')
                    ->label('اسم الشركة (عربي)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('company_name_en')
                    ->label('اسم الشركة (إنجليزي)')
                    ->maxLength(255),

                Textarea::make('about_short')
                    ->label('نبذة مختصرة عن الشركة (عربي)')
                    ->rows(3),

                Textarea::make('about_short_en')
                    ->label('نبذة مختصرة عن الشركة (إنجليزي)')
                    ->rows(3),
            ])->columns(2),

            Section::make('اللوجو')->schema([

                FileUpload::make('logo_path')
                    ->label('لوجو الشركة')
                    ->disk('public')
                    ->directory('logos')
                    ->visibility('public')
                    ->image()
                    ->fetchFileInformation(false)
                    ->imagePreviewHeight('120')
                    ->openable()
                    ->downloadable()
                    ->deletable()
                    ->deleteUploadedFileUsing(function (?string $file): void {
                        if ($file) {
                            Storage::disk('public')->delete($file);
                        }
                    })
                    ->nullable()
                    ->columnSpanFull()
                    ->maxSize(2048),

                Action::make('removeLogo')
                    ->label('إزالة اللوجو الحالي')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->visible(fn ($record) => filled($record?->logo_path))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if ($record->logo_path) {
                            Storage::disk('public')->delete($record->logo_path);
                            $record->update(['logo_path' => null]);
                        }
                    }),

            ]),

            Section::make('بيانات التواصل')->schema([
                TextInput::make('phone')->label('هاتف')->maxLength(255),
                TextInput::make('whatsapp')->label('واتساب')->maxLength(255),
                TextInput::make('email')->label('البريد الإلكتروني')->email()->maxLength(255),
                TextInput::make('website')->label('الموقع الإلكتروني')->maxLength(255),
            ])->columns(2),

            Section::make('العنوان')->schema([
                TextInput::make('address')->label('العنوان (عربي)')->maxLength(255),
                TextInput::make('address_en')->label('العنوان (إنجليزي)')->maxLength(255),
            ])->columns(2),

            Section::make('السوشيال')->schema([
                TextInput::make('facebook')->label('فيسبوك')->url()->maxLength(255),
                TextInput::make('linkedin')->label('لينكدإن')->url()->maxLength(255),
                TextInput::make('twitter')->label('تويتر / X')->url()->maxLength(255),
                TextInput::make('instagram')->label('إنستجرام')->url()->maxLength(255),
                TextInput::make('youtube')->label('يوتيوب')->url()->maxLength(255),
                TextInput::make('tiktok')->label('تيك توك')->url()->maxLength(255),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')->label('اسم الشركة')->searchable(),
                TextColumn::make('phone')->label('هاتف')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')->label('البريد الإلكتروني')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('website')->label('الموقع')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('آخر تعديل')->dateTime('Y-m-d H:i'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit'   => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
