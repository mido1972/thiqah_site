<?php

namespace App\Filament\Resources\HeroSliders;

use App\Filament\Resources\HeroSliders\Pages\CreateHeroSlider;
use App\Filament\Resources\HeroSliders\Pages\EditHeroSlider;
use App\Filament\Resources\HeroSliders\Pages\ListHeroSliders;
use App\Filament\Resources\HeroSliders\RelationManagers\HeroSlidesRelationManager;
use App\Filament\Resources\HeroSliders\Schemas\HeroSliderForm;
use App\Filament\Resources\HeroSliders\Tables\HeroSlidersTable;
use App\Models\HeroSlider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HeroSliderResource extends Resource
{
    protected static ?string $model = HeroSlider::class;

    /* ================= Navigation ================= */

    protected static string|UnitEnum|null $navigationGroup = 'محتوى الموقع';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'السلايدر';
    protected static ?string $pluralLabel = 'السلايدر';
    protected static ?string $modelLabel = 'سلايدر';

    /* ================= Record ================= */

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return HeroSliderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeroSlidersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            HeroSlidesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListHeroSliders::route('/'),
            'create' => CreateHeroSlider::route('/create'),
            'edit'   => EditHeroSlider::route('/{record}/edit'),
        ];
    }
}
