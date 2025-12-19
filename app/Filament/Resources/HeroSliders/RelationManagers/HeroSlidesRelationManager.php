<?php

namespace App\Filament\Resources\HeroSliders\RelationManagers;

use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSlidesRelationManager extends RelationManager
{
    protected static string $relationship = 'slides';

    protected static ?string $recordTitleAttribute = 'title_ar';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([

            TextInput::make('title_ar')
                ->label('العنوان (عربي)')
                ->required()
                ->maxLength(255),

            TextInput::make('title_en')
                ->label('العنوان (English)')
                ->maxLength(255),

            Textarea::make('subtitle_ar')
                ->label('وصف مختصر (عربي)')
                ->rows(2),

            Textarea::make('subtitle_en')
                ->label('وصف مختصر (English)')
                ->rows(2),

            RichEditor::make('content_ar')
                ->label('النص الرئيسي (عربي)')
                ->toolbarButtons([
                    'bold', 'italic', 'underline', 'strike',
                    'h2', 'h3',
                    'bulletList', 'orderedList',
                    'blockquote',
                    'link',
                    'undo', 'redo',
                ])
                ->columnSpanFull(),

            RichEditor::make('content_en')
                ->label('النص الرئيسي (English)')
                ->toolbarButtons([
                    'bold', 'italic', 'underline', 'strike',
                    'h2', 'h3',
                    'bulletList', 'orderedList',
                    'blockquote',
                    'link',
                    'undo', 'redo',
                ])
                ->columnSpanFull(),

            FileUpload::make('main_image')
                ->label('الصورة الرئيسية (خلفية السلايدر)')
                ->image()
                ->directory('hero/main')
                ->imageEditor()
                ->columnSpanFull(),

            FileUpload::make('overlay_images')
                ->label('صور إضافية فوق الخلفية (اختياري)')
                ->image()
                ->multiple()
                ->reorderable()
                ->directory('hero/overlays')
                ->columnSpanFull(),

            TextInput::make('cta_label_ar')
                ->label('نص الزر (عربي)')
                ->maxLength(100),

            TextInput::make('cta_label_en')
                ->label('نص الزر (English)')
                ->maxLength(100),

            TextInput::make('cta_url')
                ->label('رابط الزر')
                ->url()
                ->maxLength(255),

            TextInput::make('order')
                ->label('الترتيب')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('مفعل')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_ar')
                    ->label('العنوان')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('main_image')
                    ->label('الصورة')
                    ->square(),

                Tables\Columns\TextColumn::make('order')
                    ->label('ترتيب')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('مفعل')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
