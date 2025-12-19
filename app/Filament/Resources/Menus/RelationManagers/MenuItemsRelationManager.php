<?php

namespace App\Filament\Resources\Menus\RelationManagers;

use App\Models\MenuItem;
use App\Models\Page;

use Illuminate\Validation\Rule;

use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Resources\RelationManagers\RelationManager;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get as SchemaGet;

use Filament\Tables;
use Filament\Tables\Table;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'عناصر القائمة';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('عنصر القائمة')
                ->columns(2)
                ->schema([
                    TextInput::make('title_ar')
                        ->label('الاسم (عربي)')
                        ->required()
                        // ✅ منع تكرار العنوان داخل نفس القائمة + نفس الأب
                        ->rules([
                            fn ($record) => Rule::unique('menu_items', 'title_ar')
                                ->where('menu_id', $this->getOwnerRecord()->id)
                                ->where('parent_id', request()->input('parent_id')) // fallback
                                ->ignore($record?->id),
                        ]),

                    TextInput::make('title_en')
                        ->label('الاسم (إنجليزي)')
                        ->nullable(),

                    Select::make('parent_id')
                        ->label('عنصر أب (اختياري)')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        // ✅ نجيب الآباء من نفس القائمة فقط + نستبعد السجل الحالي
                        ->options(function ($record) {
                            return MenuItem::query()
                                ->where('menu_id', $this->getOwnerRecord()->id)
                                ->when($record?->id, fn ($q) => $q->where('id', '!=', $record->id))
                                ->orderBy('title_ar')
                                ->pluck('title_ar', 'id')
                                ->toArray();
                        }),

                    Select::make('type')
                        ->label('النوع')
                        ->options([
                            'page' => 'صفحة من الصفحات',
                            'url'  => 'رابط (Route/URL)',
                        ])
                        ->default('page')
                        ->required()
                        ->reactive(),

                    Select::make('page_id')
                        ->label('الصفحة المرتبطة')
                        ->options(fn () => Page::query()
                            ->orderBy('title_ar')
                            ->pluck('title_ar', 'id')
                            ->toArray()
                        )
                        ->searchable()
                        ->preload()
                        ->visible(fn (SchemaGet $get) => $get('type') === 'page')
                        ->nullable(),

                    TextInput::make('url')
                        ->label('الرابط / Fragment')
                        ->helperText('مثال: /services أو https://... أو #goals')
                        ->visible(fn (SchemaGet $get) => $get('type') === 'url')
                        ->nullable(),

                    Toggle::make('open_in_new_tab')
                        ->label('فتح في تبويب جديد')
                        ->default(false),

                    TextInput::make('order')
                        ->label('ترتيب العنصر')
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_active')
                        ->label('نشط')
                        ->default(true),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) =>
                $query
                    // ✅ ترتيب: Parent ثم Child حسب order
                    ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
                    ->orderBy('parent_id')
                    ->orderBy('order')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title_ar')
                    ->label('العنوان')
                    ->searchable(),

                // ✅ عمود الأب: هتشوف “أهدافنا” تحت مين
                Tables\Columns\TextColumn::make('parent.title_ar')
                    ->label('الأب')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => $state === 'page' ? 'صفحة' : 'رابط'),

                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
