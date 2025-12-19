<?php

namespace App\Support;

use App\Models\Setting;

class Settings
{
    protected static ?Setting $row = null;
    protected static bool $loaded = false;

    protected static function row(): ?Setting
    {
        if (!self::$loaded) {
            self::$row = Setting::query()->first();
            self::$loaded = true;
        }

        return self::$row;
    }

    public static function all(): array
    {
        return self::row()?->toArray() ?? [];
    }

    public static function get(string $key, $default = null)
    {
        $row = self::row();

        if (!$row) {
            return $default;
        }

        // لو العمود موجود كـ attribute
        $attrs = $row->getAttributes();
        if (array_key_exists($key, $attrs)) {
            return $row->{$key};
        }

        return $default;
    }
}
