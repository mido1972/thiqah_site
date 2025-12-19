<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',        // ✅ لازم عشان الفورم يحفظ اسم القائمة
        'location',    // ✅ header | footer | both
        'code',
        'order',
        'is_active',

        // (اختياري) لو لسه بتستخدمهم في أجزاء أخرى:
        'title_ar',
        'title_en',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->orderBy('order');
    }

    public function rootItems(): HasMany
    {
        return $this->items()->whereNull('parent_id');
    }
}
