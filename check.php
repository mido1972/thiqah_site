<?php
require __DIR__ . "/vendor/autoload.php";

$classes = [
    \Filament\Schemas\Components\Section::class,
    \Filament\Forms\Components\TextInput::class,
    \Filament\Forms\Components\Textarea::class,
    \Filament\Forms\Components\FileUpload::class,
    \Filament\Tables\Actions\EditAction::class,
];

foreach ($classes as $c) {
    echo $c . " => " . (class_exists($c) ? "OK" : "NO") . PHP_EOL;
}
