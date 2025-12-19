<?php

namespace App\View\Composers;

use App\Models\Page;
use App\Support\Settings;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class WebsiteLayoutComposer
{
    public function compose(View $view): void
    {
        // Row settings as array (from Settings helper)
        $row = Settings::all();

        // Language: ?lang=ar|en (default ar)
        $appLocale = strtolower((string) request()->query('lang', 'ar'));
        $appLocale = $appLocale === 'en' ? 'en' : 'ar';

        // ---- Normalize settings keys (map DB columns -> unified keys) ----
        $companyNameAr = $row['company_name_ar'] ?? $row['company_name'] ?? 'ثقة';
        $companyNameEn = $row['company_name_en'] ?? $row['company_name_en'] ?? 'Thiqah';

        $companyAddress = $row['address'] ?? $row['company_address'] ?? null;
        $companyPhone   = $row['phone'] ?? $row['company_phone'] ?? null;
        $companyEmail   = $row['email'] ?? $row['company_email'] ?? null;

        // logo path in DB might be: logo, logo_path, company_logo
        $logo = $row['company_logo'] ?? $row['logo'] ?? $row['logo_path'] ?? null;

        // Social columns might be named directly
        $facebook  = $row['facebook'] ?? null;
        $linkedin  = $row['linkedin'] ?? null;
        $instagram = $row['instagram'] ?? null;
        $twitter   = $row['twitter'] ?? $row['x'] ?? null;
        $youtube   = $row['youtube'] ?? null;
        $tiktok    = $row['tiktok'] ?? null;
        $whatsapp  = $row['whatsapp'] ?? null;

        // SEO defaults (optional columns)
        $metaTitleAr = $row['meta_title_ar'] ?? null;
        $metaTitleEn = $row['meta_title_en'] ?? null;

        $metaDescAr  = $row['meta_description_ar'] ?? null;
        $metaDescEn  = $row['meta_description_en'] ?? null;

        $metaKeysAr  = $row['meta_keywords_ar'] ?? null;
        $metaKeysEn  = $row['meta_keywords_en'] ?? null;

        $baseTitle = $appLocale === 'en' ? $companyNameEn : $companyNameAr;

        $defaultMetaTitle = $appLocale === 'en'
            ? ($metaTitleEn ?? $companyNameEn)
            : ($metaTitleAr ?? $companyNameAr);

        $defaultMetaDesc = $appLocale === 'en'
            ? ($metaDescEn ?? '')
            : ($metaDescAr ?? '');

        $defaultMetaKeys = $appLocale === 'en'
            ? ($metaKeysEn ?? '')
            : ($metaKeysAr ?? '');

        // ---- Pages for header/footer (safe fallbacks) ----
        $headerPages = collect();
        $footerPages = collect();

        try {
            // If scopes exist (recommended)
            if (method_exists(Page::class, 'scopeHeader')) {
                $headerPages = Page::query()->header()->get();
            } else {
                $q = Page::query();
                // fallback if columns exist
                if (Schema::hasColumn((new Page)->getTable(), 'show_in_header')) {
                    $q->where('show_in_header', 1);
                }
                $headerPages = $q->orderBy('sort_order')->get();
            }

            if (method_exists(Page::class, 'scopeFooter')) {
                $footerPages = Page::query()->footer()->get();
            } else {
                $q = Page::query();
                if (Schema::hasColumn((new Page)->getTable(), 'show_in_footer')) {
                    $q->where('show_in_footer', 1);
                }
                $footerPages = $q->orderBy('sort_order')->get();
            }
        } catch (\Throwable $e) {
            // Keep empty to avoid breaking frontend if schema differs
            $headerPages = collect();
            $footerPages = collect();
        }

        // Language switch URLs (preserve other query params)
        $baseQuery     = request()->query();
        $switchToArUrl = request()->url() . '?' . http_build_query(array_merge($baseQuery, ['lang' => 'ar']));
        $switchToEnUrl = request()->url() . '?' . http_build_query(array_merge($baseQuery, ['lang' => 'en']));

        // Social normalized array
        $socialLinks = [
            'facebook'  => $facebook,
            'linkedin'  => $linkedin,
            'instagram' => $instagram,
            'twitter'   => $twitter,
            'youtube'   => $youtube,
            'tiktok'    => $tiktok,
            'whatsapp'  => $whatsapp,
        ];

        $hasSocial = collect($socialLinks)->filter()->isNotEmpty();

        // Provide unified keys expected by views
        $siteSettings = array_merge($row, [
            'company_name'    => $companyNameAr,
            'company_name_en' => $companyNameEn,
            'company_address' => $companyAddress,
            'company_phone'   => $companyPhone,
            'company_email'   => $companyEmail,
            'company_logo'    => $logo,

            'facebook'  => $facebook,
            'linkedin'  => $linkedin,
            'instagram' => $instagram,
            'twitter'   => $twitter,
            'youtube'   => $youtube,
            'tiktok'    => $tiktok,
            'whatsapp'  => $whatsapp,
        ]);

        $view->with([
            'siteSettings'       => $siteSettings,
            'appLocale'          => $appLocale,
            'baseTitle'          => $baseTitle,

            'defaultMetaTitle'   => $defaultMetaTitle,
            'defaultMetaDesc'    => $defaultMetaDesc,
            'defaultMetaKeys'    => $defaultMetaKeys,

            'headerPages'        => $headerPages,
            'footerPages'        => $footerPages,

            'switchToArUrl'      => $switchToArUrl,
            'switchToEnUrl'      => $switchToEnUrl,

            'socialLinks'        => $socialLinks,
            'hasSocial'          => $hasSocial,
        ]);
    }
}
