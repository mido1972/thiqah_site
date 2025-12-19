<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'type',        // page | url
        'page_id',
        'url',         // ممكن نخليها URL كامل أو fragment مثل #goals
        'title_ar',
        'title_en',
        'order',
        'is_active',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * رابط موحّد لأي MenuItem:
     * - لو type=page => /{slug} + (اختياري fragment لو url يبدأ بـ #)
     * - لو type=url  => url كما هو
     */
    public function getHrefAttribute(): string
    {
        // 1) External / custom URL
        if (($this->type ?? '') === 'url') {
            return $this->url ?: '#';
        }

        // 2) Linked to CMS page
        if (($this->type ?? '') === 'page' && $this->page) {
            $base = url('/' . ltrim($this->page->slug, '/'));

            // لو الـ url فيها fragment فقط مثل "#goals" نضيفها
            if (is_string($this->url) && str_starts_with($this->url, '#')) {
                return $base . $this->url;
            }

            return $base;
        }

        return '#';
    }
}
