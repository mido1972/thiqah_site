<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Resources\Menus\MenuResource\Pages;
use App\Filament\Resources\Menus\RelationManagers\MenuItemsRelationManager;
use App\Models\Menu;
use BackedEnum;
use UnitEnum;

use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationLabel = 'القوائم';
    protected static ?string $pluralModelLabel = 'القوائم';
    protected static ?string $modelLabel = 'قائمة';
    protected static UnitEnum|string|null $navigationGroup = 'إعدادات الموقع';
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * فورم إنشاء / تعديل القائمة
     */
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات القائمة')
                ->schema([
                    TextInput::make('name')
                        ->label('اسم القائمة')
                        ->required()
                        ->maxLength(255),

                    Select::make('location')
                        ->label('مكان الظهور')
                        ->options([
                            'header' => 'الهيدر',
                            'footer' => 'الفوتر',
                            'both'   => 'الهيدر + الفوتر',
                        ])
                        ->default('header')
                        ->required(),

                    TextInput::make('code')
                        ->label('كود القائمة (اختياري)')
                        ->maxLength(50)
                        ->helperText('تقدر تستدعي القائمة بالكود من الواجهة الأمامية لو حابب'),

                    TextInput::make('order')
                        ->label('الترتيب')
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_active')
                        ->label('مفعّلة')
                        ->default(true),
                ]),
        ]);
    }

    /**
     * جدول القوائم
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('اسم القائمة')
                    ->searchable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('المكان')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'header' => 'الهيدر',
                        'footer' => 'الفوتر',
                        'both'   => 'الهيدر + الفوتر',
                        default  => $state,
                    }),

                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * RelationManagers الخاصة بالقائمة
     */
    public static function getRelations(): array
    {
        return [
            MenuItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'view'   => Pages\ViewMenu::route('/{record}'),
            'edit'   => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
