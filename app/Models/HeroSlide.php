<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSlide extends Model
{
    protected $fillable = [
        'slider_id',
        'title_ar', 'title_en',
        'subtitle_ar', 'subtitle_en',
        'content_ar', 'content_en',
        'cta_label_ar', 'cta_label_en',
        'cta_url',
        'main_image',
        'overlay_images',
        'order',
        'is_active',
    ];

    protected $casts = [
        'overlay_images' => 'array',
        'is_active' => 'boolean',
    ];

    public function slider(): BelongsTo
    {
        return $this->belongsTo(HeroSlider::class, 'slider_id');
    }
}
