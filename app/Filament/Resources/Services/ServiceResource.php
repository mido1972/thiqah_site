<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceResource\Pages;
use App\Models\Service;

use BackedEnum;
use UnitEnum;

use Filament\Resources\Resource;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static UnitEnum|string|null $navigationGroup = 'إعدادات الموقع';
    protected static ?string $navigationLabel = 'الخدمات / الأنظمة';
    protected static ?string $pluralModelLabel = 'الخدمات / الأنظمة';
    protected static ?string $modelLabel = 'خدمة / نظام';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('بيانات الخدمة')->schema([
                TextInput::make('code')
                    ->label('الكود')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                TextInput::make('title_ar')
                    ->label('العنوان (عربي)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('title_en')
                    ->label('العنوان (إنجليزي)')
                    ->maxLength(255),

                TextInput::make('icon_class')
                    ->label('أيقونة Font Awesome')
                    ->placeholder('fa-calculator أو fa-solid fa-user')
                    ->maxLength(100),

                TextInput::make('sort_order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('مفعل')
                    ->default(true),
            ])->columns(2),

            Section::make('الوصف')->schema([
                Textarea::make('description_ar')
                    ->label('الوصف (عربي)')
                    ->rows(6)
                    ->columnSpanFull(),

                Textarea::make('description_en')
                    ->label('الوصف (إنجليزي)')
                    ->rows(6)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('الكود')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title_ar')
                    ->label('العنوان (عربي)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title_en')
                    ->label('العنوان (إنجليزي)')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('مفعل')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('آخر تعديل')
                    ->dateTime('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
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
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
