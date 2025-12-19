@php
    // ✅ 0) Normalize site settings source (Model or array)
    if (!isset($siteSettings) || empty($siteSettings)) {
        if (isset($settings) && $settings) {
            $siteSettings = is_array($settings) ? $settings : $settings->toArray();
        } else {
            $siteSettings = [];
        }
    }

    // ===== Locale =====
    $appLocale = $locale ?? request()->query('lang', 'ar');
    $appLocale = strtolower((string) $appLocale) === 'en' ? 'en' : 'ar';
    $isRtl = $appLocale === 'ar';

    // Safe settings getter from array
    $ss = fn ($key, $default = null) => data_get($siteSettings, $key, $default);

    // SEO defaults
    $defaultTitle = $appLocale === 'en'
        ? ($ss('meta_title_en', $ss('company_name_en', $ss('site_name_en', 'Thiqah I-Tech'))))
        : ($ss('meta_title_ar', $ss('company_name', $ss('site_name', 'ثقة لتقنية نظم المعلومات'))));

    $defaultDesc = $appLocale === 'en'
        ? ((string) $ss('meta_description_en', ''))
        : ((string) $ss('meta_description_ar', ''));

    $pageTitle = trim((string)($__env->yieldContent('title'))) ?: $defaultTitle;
    $pageDesc  = trim((string)($__env->yieldContent('meta_description'))) ?: $defaultDesc;

    $switchToArUrl = $switchToArUrl ?? (url()->current() . '?lang=ar');
    $switchToEnUrl = $switchToEnUrl ?? (url()->current() . '?lang=en');

    // ✅ Favicon: supports full URL or storage path or raw filename
    $faviconRaw = (string) ($ss('favicon') ?? '');
    $faviconUrl = null;

    if ($faviconRaw !== '') {
        if (str_starts_with($faviconRaw, 'http://') || str_starts_with($faviconRaw, 'https://')) {
            $faviconUrl = $faviconRaw;
        } else {
            $faviconRaw = ltrim($faviconRaw, '/');
            $faviconRaw = preg_replace('#^storage/#', '', $faviconRaw); // remove leading "storage/"
            $faviconUrl = asset('storage/' . $faviconRaw);
        }
    }
@endphp

<!doctype html>
<html lang="{{ $appLocale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $pageTitle }}</title>

    @if(!empty($pageDesc))
        <meta name="description" content="{{ $pageDesc }}">
    @endif

    @if(!empty($faviconUrl))
        <link rel="icon" href="{{ $faviconUrl }}">
    @else
        <link rel="icon" href="data:,">
    @endif

    @yield('extra_head')

    {{-- ✅ Keep Vite ONLY here --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

@include('website.partials.header', [
    'siteSettings' => $siteSettings,
    'appLocale' => $appLocale,
    'isRtl' => $isRtl,
    'switchToArUrl' => $switchToArUrl,
    'switchToEnUrl' => $switchToEnUrl,
])

<main>
    @yield('content')
</main>

@include('website.partials.footer', [
    'siteSettings' => $siteSettings,
    'appLocale' => $appLocale,
    'isRtl' => $isRtl,
])

</body>
</html>
