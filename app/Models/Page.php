<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $slug
 * @property string|null $title_ar
 * @property string|null $title_en
 * @property string|null $content_ar
 * @property string|null $content_en
 * @property string|null $meta_title_ar
 * @property string|null $meta_title_en
 * @property string|null $meta_description_ar
 * @property string|null $meta_description_en
 * @property bool $is_active
 * @property bool $show_in_header
 * @property bool $show_in_footer
 * @property int $sort_order
 */
class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';

    protected $fillable = [
        'slug',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'meta_title_ar',
        'meta_title_en',
        'meta_description_ar',
        'meta_description_en',
        'is_active',
        'show_in_header',
        'show_in_footer',
        'sort_order',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'show_in_header' => 'boolean',
        'show_in_footer' => 'boolean',
        'sort_order'     => 'integer',
    ];

    public static function normalizeLocale(?string $locale): string
    {
        $locale = strtolower($locale ?? 'ar');
        return $locale === 'en' ? 'en' : 'ar';
    }

    public function getTitle(string $locale = 'ar'): string
    {
        $locale = self::normalizeLocale($locale);

        if ($locale === 'en') {
            return $this->title_en ?: ($this->title_ar ?: $this->slug);
        }

        return $this->title_ar ?: ($this->title_en ?: $this->slug);
    }

    public function getContent(string $locale = 'ar'): ?string
    {
        $locale = self::normalizeLocale($locale);

        if ($locale === 'en') {
            return $this->content_en ?: $this->content_ar;
        }

        return $this->content_ar ?: $this->content_en;
    }

    public function getMetaTitle(string $locale = 'ar'): string
    {
        $locale = self::normalizeLocale($locale);

        if ($locale === 'en') {
            return $this->meta_title_en
                ?: $this->meta_title_ar
                ?: $this->getTitle('en');
        }

        return $this->meta_title_ar
            ?: $this->meta_title_en
            ?: $this->getTitle('ar');
    }

    public function getMetaDescription(string $locale = 'ar'): ?string
    {
        $locale = self::normalizeLocale($locale);

        if ($locale === 'en') {
            return $this->meta_description_en ?: $this->meta_description_ar;
        }

        return $this->meta_description_ar ?: $this->meta_description_en;
    }

    /* Scopes */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHeader($query)
    {
        return $query
            ->active()
            ->where('show_in_header', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function scopeFooter($query)
    {
        return $query
            ->active()
            ->where('show_in_footer', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
