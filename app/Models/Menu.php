<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'code',
        'title_ar',
        'title_en',
        'order',
        'is_active',
    ];

    // ✅ كل العناصر (Parent + Child)
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->orderBy('order');
    }

    // ✅ العناصر الرئيسية فقط (استخدمها لو تحب في الواجهة)
    public function rootItems(): HasMany
    {
        return $this->items()->whereNull('parent_id');
    }
}
