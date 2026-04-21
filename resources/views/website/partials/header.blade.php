@php
    $appLocale = $appLocale ?? 'ar';
    $isRtl = ($appLocale === 'ar');

    $itemTitle = fn ($item) =>
        $appLocale === 'en'
            ? ($item->title_en ?: $item->title_ar)
            : ($item->title_ar ?: $item->title_en);

    $itemUrl = function ($item) {
        if (!empty($item->route_name) && \Route::has($item->route_name)) {
            try {
                return route($item->route_name);
            } catch (\Throwable $e) {
            }
        }

        if (($item->type ?? '') === 'page' && !empty($item->page_id) && isset($item->page) && $item->page) {
            $slug = $item->page->slug ?? null;
            if ($slug) {
                return url('/' . ltrim($slug, '/'));
            }
        }

        $u = trim((string) ($item->url ?? ''));
        if ($u === '') return '#';
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://')) return $u;
        if (str_starts_with($u, '#') || str_starts_with($u, 'mailto:') || str_starts_with($u, 'tel:')) return $u;

        return url('/' . ltrim($u, '/'));
    };

    $itemTarget = fn ($item) =>
        !empty($item->open_in_new_tab)
            ? ' target="_blank" rel="noopener noreferrer"'
            : '';

    $companyNameAr = $companyNameAr ?? ($siteSettings['company_name'] ?? 'ثقة');
    $companyNameEn = $companyNameEn ?? ($siteSettings['company_name_en'] ?? 'Thiqah');
    $companyLogo = $companyLogo ?? ($siteSettings['company_logo'] ?? ($siteSettings['logo'] ?? null));

    $topPhone = $topPhone ?? ($companyPhone ?? null);
    $topEmail = $topEmail ?? ($companyEmail ?? null);

    $ctaLabel = $ctaLabel ?? ($appLocale === 'en' ? 'Get a Quote' : 'اطلب عرض سعر');
    $ctaUrl = $ctaUrl ?? url('/contact');

    $headerMenuTree = $headerMenuTree ?? collect();

    $tagline = $appLocale === 'en'
        ? (data_get($siteSettings ?? [], 'header_tagline_en')
            ?? data_get($siteSettings ?? [], 'about_short_en')
            ?? data_get($siteSettings ?? [], 'company_tagline_en')
            ?? '')
        : (data_get($siteSettings ?? [], 'header_tagline_ar')
            ?? data_get($siteSettings ?? [], 'about_short')
            ?? data_get($siteSettings ?? [], 'company_tagline_ar')
            ?? '');

    $tagline = trim((string) $tagline);

    if ($tagline === '') {
        $tagline = $appLocale === 'en'
            ? 'ERP, HR and Contracting Systems'
            : 'حلول ERP و HR وإدارة العقود';
    }

    $social = $socialLinks ?? [
        'facebook' => data_get($siteSettings ?? [], 'facebook'),
        'instagram' => data_get($siteSettings ?? [], 'instagram'),
        'linkedin' => data_get($siteSettings ?? [], 'linkedin'),
        'twitter' => data_get($siteSettings ?? [], 'twitter') ?? data_get($siteSettings ?? [], 'x'),
    ];

    $safeHref = function ($url) {
        $u = trim((string) $url);
        if ($u === '') return null;
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://')) return $u;
        return $u;
    };

    $logoUrl = null;
    if (!empty($companyLogo)) {
        $logoUrl = str_starts_with($companyLogo, 'http')
            ? $companyLogo
            : asset('storage/' . ltrim(preg_replace('#^storage/#', '', $companyLogo), '/'));
    }
@endphp

<header class="site-header">
    <div class="topbar hidden md:block">
        <div class="mx-auto max-w-7xl px-4">
            <div class="flex h-10 items-center justify-between text-sm">
                <div class="flex items-center gap-5">
                    @if(!empty($topEmail))
                        <a class="topbar-link" href="mailto:{{ $topEmail }}">
                            <span class="topbar-icon">✉</span>
                            <span>{{ $topEmail }}</span>
                        </a>
                    @endif

                    @if(!empty($topPhone))
                        <a class="topbar-link" href="tel:{{ $topPhone }}" dir="ltr">
                            <span class="topbar-icon">☎</span>
                            <span>{{ $topPhone }}</span>
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @foreach(['facebook' => 'f', 'instagram' => 'ig', 'linkedin' => 'in', 'twitter' => 'x'] as $key => $label)
                        @if($safeHref($social[$key] ?? null))
                            <a href="{{ $safeHref($social[$key]) }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="{{ $key }}">
                                {{ $label }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4">
        <div class="flex min-h-[5.75rem] items-center justify-between gap-5 py-3">
            <a href="{{ url('/') }}" class="brand-lockup">
                @if($logoUrl)
                    <span class="brand-logo-frame">
                        <img src="{{ $logoUrl }}" alt="{{ $appLocale === 'en' ? $companyNameEn : $companyNameAr }}" class="brand-logo-img" loading="lazy">
                    </span>
                @else
                    <span class="brand-logo-fallback">T</span>
                @endif

                <span class="min-w-0 leading-tight">
                    <span class="brand-name">{{ $appLocale === 'en' ? $companyNameEn : $companyNameAr }}</span>
                    <span class="brand-tagline">{{ $tagline }}</span>
                </span>
            </a>

            <nav class="main-nav hidden md:flex" aria-label="Main navigation">
                @foreach($headerMenuTree as $item)
                    @php
                        $hasChildren = isset($item->children) && $item->children->count() > 0;
                        $href = $itemUrl($item);
                    @endphp

                    @if($hasChildren)
                        <div class="nav-item group relative">
                            <button type="button" class="nav-link">
                                {{ $itemTitle($item) }}
                                <span class="nav-caret">▾</span>
                            </button>

                            <div class="dropdown-wrap absolute {{ $isRtl ? 'right-0' : 'left-0' }} top-full z-50 w-72 opacity-0 invisible transition group-hover:visible group-hover:opacity-100">
                                <div class="dropdown-panel">
                                    @foreach($item->children as $child)
                                        <a href="{{ $itemUrl($child) }}"{!! $itemTarget($child) !!} class="dropdown-link">
                                            {{ $itemTitle($child) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $href }}"{!! $itemTarget($item) !!} class="nav-link">
                            {{ $itemTitle($item) }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ $ctaUrl }}" class="header-cta hidden sm:inline-flex">
                    {{ $ctaLabel }}
                </a>

                <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="mobile-menu-button md:hidden" aria-label="Open menu">
                    ☰
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="hidden pb-4 md:hidden">
            <div class="mobile-menu-panel">
                @foreach($headerMenuTree as $item)
                    @php
                        $hasChildren = isset($item->children) && $item->children->count() > 0;
                    @endphp

                    @if($hasChildren)
                        <details class="mobile-details">
                            <summary class="mobile-summary">{{ $itemTitle($item) }}</summary>
                            <div class="px-2 pb-2">
                                @foreach($item->children as $child)
                                    <a href="{{ $itemUrl($child) }}"{!! $itemTarget($child) !!} class="mobile-link">
                                        {{ $itemTitle($child) }}
                                    </a>
                                @endforeach
                            </div>
                        </details>
                    @else
                        <a href="{{ $itemUrl($item) }}"{!! $itemTarget($item) !!} class="mobile-link">
                            {{ $itemTitle($item) }}
                        </a>
                    @endif
                @endforeach

                <a href="{{ $ctaUrl }}" class="mobile-cta">
                    {{ $ctaLabel }}
                </a>
            </div>
        </div>
    </div>
</header>
