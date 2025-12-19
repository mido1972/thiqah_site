<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\PageResource\Pages;
use App\Models\Page;

use BackedEnum;
use UnitEnum;

use Filament\Resources\Resource;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    /* ================= Navigation ================= */

    protected static string|UnitEnum|null $navigationGroup = 'محتوى الموقع';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'الصفحات';
    protected static ?string $pluralLabel = 'الصفحات';

    /* ================= Form (Schema v4 + Forms Fields) ================= */

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('البيانات الأساسية')
                ->columns(2)
                ->schema([
                    TextInput::make('slug')
                        ->label('Slug')
                        ->helperText('مثال: about, contact, services')
                        ->required()
                        ->maxLength(190)
                        ->unique(
                            table: 'pages',
                            column: 'slug',
                            ignorable: fn ($record) => $record,
                        ),

                    TextInput::make('sort_order')
                        ->label('الترتيب')
                        ->numeric()
                        ->default(0),

                    TextInput::make('title_ar')
                        ->label('العنوان (عربي)')
                        ->required()
                        ->maxLength(190),

                    TextInput::make('title_en')
                        ->label('العنوان (إنجليزي)')
                        ->maxLength(190),

                    Toggle::make('is_active')
                        ->label('نشط')
                        ->default(true),

                    Toggle::make('show_in_header')
                        ->label('يظهر في الهيدر')
                        ->default(false),

                    Toggle::make('show_in_footer')
                        ->label('يظهر في الفوتر')
                        ->default(false),
                ]),

            Section::make('المحتوى')
                ->columns(1)
                ->schema([
                    Textarea::make('content_ar')
                        ->label('المحتوى (عربي)')
                        ->rows(12),

                    Textarea::make('content_en')
                        ->label('المحتوى (إنجليزي)')
                        ->rows(12),
                ]),
        ]);
    }

    /* ================= Table ================= */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title_ar')->label('العنوان (عربي)')->searchable(),
                TextColumn::make('title_en')->label('العنوان (إنجليزي)')->searchable(),

                IconColumn::make('is_active')->label('نشط')->boolean(),
                IconColumn::make('show_in_header')->label('هيدر')->boolean(),
                IconColumn::make('show_in_footer')->label('فوتر')->boolean(),

                TextColumn::make('sort_order')->label('الترتيب')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
