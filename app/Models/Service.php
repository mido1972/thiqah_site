<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'code',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'icon_class',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    /**
     * Scope: الخدمات المفعّلة فقط مرتبة بالترتيب.
     */
    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', true)
                     ->orderBy('sort_order')
                     ->orderBy('id');
    }
}
