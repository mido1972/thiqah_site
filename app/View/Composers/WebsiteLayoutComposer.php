<?php

namespace App\View\Composers;

use App\Models\Menu;
use App\Models\Page;
use App\Models\HeroSlider;
use App\Support\Settings;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class WebsiteLayoutComposer
{
    public function compose(View $view): void
    {
        /* =========================================================
         |  Settings + Language
         ========================================================= */

        $row = Settings::all();

        $appLocale = strtolower((string) request()->query('lang', 'ar'));
        $appLocale = $appLocale === 'en' ? 'en' : 'ar';

        $companyNameAr = $row['company_name_ar'] ?? $row['company_name'] ?? 'ثقة';
        $companyNameEn = $row['company_name_en'] ?? 'Thiqah';

        $companyAddress = $row['address'] ?? $row['company_address'] ?? null;
        $companyPhone   = $row['phone'] ?? $row['company_phone'] ?? null;
        $companyEmail   = $row['email'] ?? $row['company_email'] ?? null;

        $logo = $row['company_logo'] ?? $row['logo'] ?? $row['logo_path'] ?? null;

        /* =========================================================
         |  Social
         ========================================================= */

        $socialLinks = [
            'facebook'  => $row['facebook']  ?? null,
            'linkedin'  => $row['linkedin']  ?? null,
            'instagram' => $row['instagram'] ?? null,
            'twitter'   => $row['twitter']   ?? $row['x'] ?? null,
            'youtube'   => $row['youtube']   ?? null,
            'tiktok'    => $row['tiktok']    ?? null,
            'whatsapp'  => $row['whatsapp']  ?? null,
        ];

        $hasSocial = collect($socialLinks)->filter()->isNotEmpty();

        /* =========================================================
         |  SEO Defaults
         ========================================================= */

        $defaultMetaTitle = $appLocale === 'en'
            ? ($row['meta_title_en'] ?? $companyNameEn)
            : ($row['meta_title_ar'] ?? $companyNameAr);

        $defaultMetaDesc = $appLocale === 'en'
            ? ($row['meta_description_en'] ?? '')
            : ($row['meta_description_ar'] ?? '');

        $defaultMetaKeys = $appLocale === 'en'
            ? ($row['meta_keywords_en'] ?? '')
            : ($row['meta_keywords_ar'] ?? '');

        /* =========================================================
         |  Pages (Header / Footer)
         ========================================================= */

        $headerPages = collect();
        $footerPages = collect();

        try {
            if (method_exists(Page::class, 'scopeHeader')) {
                $headerPages = Page::query()->header()->get();
            } elseif (Schema::hasColumn((new Page)->getTable(), 'show_in_header')) {
                $headerPages = Page::query()
                    ->where('show_in_header', 1)
                    ->orderBy('sort_order')
                    ->get();
            }

            if (method_exists(Page::class, 'scopeFooter')) {
                $footerPages = Page::query()->footer()->get();
            } elseif (Schema::hasColumn((new Page)->getTable(), 'show_in_footer')) {
                $footerPages = Page::query()
                    ->where('show_in_footer', 1)
                    ->orderBy('sort_order')
                    ->get();
            }
        } catch (\Throwable $e) {
            $headerPages = collect();
            $footerPages = collect();
        }

        /* =========================================================
         |  Header Menu (Menus + MenuItems)
         ========================================================= */

        $headerMenuTree = collect();

        try {
            $menu = Menu::query()
                ->where('location', 'header')
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->orderBy('order')
                ->first();

            if ($menu) {
                $items = $menu->items()
                    ->where('is_active', 1)
                    ->orderBy('order')
                    ->get();

                $parents  = $items->whereNull('parent_id')->values();
                $children = $items->whereNotNull('parent_id')->groupBy('parent_id');

                $headerMenuTree = $parents->map(function ($parent) use ($children) {
                    $parent->children = ($children[$parent->id] ?? collect())->values();
                    return $parent;
                });
            }
        } catch (\Throwable $e) {
            $headerMenuTree = collect();
        }

        /* =========================================================
         |  ✅ Hero Slider (Home)
         ========================================================= */

        $heroSlider = null;

        try {
            $heroSlider = HeroSlider::query()
                ->where('location', 'home')
                ->where('is_active', 1)
                ->with([
                    'slides' => function ($q) {
                        $q->where('is_active', 1)
                          ->orderBy('order');
                    }
                ])
                ->first();
        } catch (\Throwable $e) {
            $heroSlider = null;
        }

        /* =========================================================
         |  Language Switch
         ========================================================= */

        $baseQuery     = request()->query();
        $switchToArUrl = request()->url() . '?' . http_build_query(array_merge($baseQuery, ['lang' => 'ar']));
        $switchToEnUrl = request()->url() . '?' . http_build_query(array_merge($baseQuery, ['lang' => 'en']));

        /* =========================================================
         |  Share With Views
         ========================================================= */

        $view->with([
            // Settings
            'siteSettings'     => $row,
            'appLocale'        => $appLocale,

            // Company
            'companyNameAr'    => $companyNameAr,
            'companyNameEn'    => $companyNameEn,
            'companyPhone'     => $companyPhone,
            'companyEmail'     => $companyEmail,
            'companyLogo'      => $logo,
            'companyAddress'   => $companyAddress,

            // SEO
            'defaultMetaTitle' => $defaultMetaTitle,
            'defaultMetaDesc'  => $defaultMetaDesc,
            'defaultMetaKeys'  => $defaultMetaKeys,

            // Pages
            'headerPages'      => $headerPages,
            'footerPages'      => $footerPages,

            // Header Menu
            'headerMenuTree'   => $headerMenuTree,

            // TopBar
            'topPhone'         => $companyPhone,
            'topEmail'         => $companyEmail,

            // CTA
            'ctaLabel'         => $appLocale === 'en' ? 'Get a Quote' : 'اطلب عرض سعر',
            'ctaUrl'           => url('/contact'),

            // Social
            'socialLinks'      => $socialLinks,
            'hasSocial'        => $hasSocial,

            // Language switch
            'switchToArUrl'    => $switchToArUrl,
            'switchToEnUrl'    => $switchToEnUrl,

            // ✅ Hero Slider
            'heroSlider'       => $heroSlider,
        ]);
    }
}
