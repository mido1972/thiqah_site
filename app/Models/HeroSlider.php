<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeroSlider extends Model
{
    protected $fillable = [
        'name',
        'location',
        'is_active',
    ];

    public function slides(): HasMany
    {
        return $this->hasMany(HeroSlide::class, 'slider_id')->orderBy('order');
    }
}
