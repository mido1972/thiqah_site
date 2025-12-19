@php
    $appLocale = $appLocale ?? 'ar';
    $isRtl = ($appLocale === 'ar');

    // Titles
    $itemTitle = fn ($item) =>
        $appLocale === 'en'
            ? ($item->title_en ?: $item->title_ar)
            : ($item->title_ar ?: $item->title_en);

    // URL builder (DB columns confirmed)
    $itemUrl = function ($item) {
        // 1) route_name
        if (!empty($item->route_name) && \Route::has($item->route_name)) {
            try { return route($item->route_name); } catch (\Throwable $e) {}
        }

        // 2) type=page + page relation (optional)
        if (($item->type ?? '') === 'page' && !empty($item->page_id)) {
            if (isset($item->page) && $item->page) {
                $slug = $item->page->slug ?? null;
                if ($slug) return url('/' . ltrim($slug, '/'));
            }
        }

        // 3) url (absolute, anchor, mailto, tel)
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

    // Safe fallbacks (لو الـ composer ما بعتش)
    $companyNameAr = $companyNameAr ?? ($siteSettings['company_name'] ?? 'ثقة');
    $companyNameEn = $companyNameEn ?? ($siteSettings['company_name_en'] ?? 'Thiqah');
    $companyLogo   = $companyLogo   ?? ($siteSettings['company_logo'] ?? ($siteSettings['logo'] ?? null));

    // phone/email from composer
    $topPhone = $topPhone ?? ($companyPhone ?? null);
    $topEmail = $topEmail ?? ($companyEmail ?? null);

    // CTA from composer
    $ctaLabel = $ctaLabel ?? ($appLocale === 'en' ? 'Get a Quote' : 'اطلب عرض سعر');
    $ctaUrl   = $ctaUrl   ?? url('/contact');

    // Menu tree from composer
    $headerMenuTree = $headerMenuTree ?? collect();

    // ✅ Tagline from control panel (Settings)
    // Try common keys safely
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

    // fallback (only if empty)
    if ($tagline === '') {
        $tagline = $appLocale === 'en'
            ? 'ERP • HR • Contracts'
            : 'حلول ERP و HR وإدارة العقود';
    }

    // ✅ Social links (from composer/siteSettings)
    $social = $socialLinks ?? [
        'facebook'  => data_get($siteSettings ?? [], 'facebook'),
        'instagram' => data_get($siteSettings ?? [], 'instagram'),
        'linkedin'  => data_get($siteSettings ?? [], 'linkedin'),
        'twitter'   => data_get($siteSettings ?? [], 'twitter') ?? data_get($siteSettings ?? [], 'x'),
    ];

    $safeHref = function ($url) {
        $u = trim((string) $url);
        if ($u === '') return null;
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://')) return $u;
        return $u;
    };
@endphp

<header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">
    {{-- Topbar (thin + professional) --}}
    <div class="hidden md:block bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="h-9 flex items-center justify-between text-xs">

                {{-- ✅ Email + Phone with icons --}}
                <div class="flex items-center gap-4 opacity-90">
                    @if(!empty($topEmail))
                        <a class="hover:opacity-100 transition inline-flex items-center gap-1.5"
                           href="mailto:{{ $topEmail }}">
                            {{-- Email icon --}}
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16
                                         c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            <span>{{ $topEmail }}</span>
                        </a>
                    @endif

                    @if(!empty($topPhone))
                        <a class="hover:opacity-100 transition inline-flex items-center gap-1.5"
                           href="tel:{{ $topPhone }}" dir="ltr">
                            {{-- Phone icon --}}
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6.6 10.8c1.4 2.7 3.9 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.3
                                         1.2.4 2.5.6 3.8.6.6 0 1 .4 1 1V20
                                         c0 .6-.4 1-1 1C10.4 21 3 13.6 3 4
                                         c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1
                                         0 1.3.2 2.6.6 3.8.1.4 0 .8-.3 1.1L6.6 10.8z"/>
                            </svg>
                            <span>{{ $topPhone }}</span>
                        </a>
                    @endif
                </div>

                {{-- ✅ Social (small SVG icons - code only, no images) --}}
                <div class="flex items-center gap-2 opacity-90">
                    @if($safeHref($social['twitter'] ?? null))
                        <a href="{{ $safeHref($social['twitter']) }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex h-7 w-7 items-center justify-center rounded-lg hover:bg-white/10 transition"
                           aria-label="X">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true">
                                <path d="M18.9 2H22l-6.8 7.8L23 22h-6.4l-5-6.6L5.7 22H2.6l7.3-8.4L1.6 2H8l4.5 6.1L18.9 2zm-1.1 18h1.8L7.1 3.9H5.2L17.8 20z"/>
                            </svg>
                        </a>
                    @endif

                    @if($safeHref($social['linkedin'] ?? null))
                        <a href="{{ $safeHref($social['linkedin']) }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex h-7 w-7 items-center justify-center rounded-lg hover:bg-white/10 transition"
                           aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true">
                                <path d="M4.98 3.5A2.5 2.5 0 1 1 5 8.5a2.5 2.5 0 0 1-.02-5zM3 9h4v12H3V9zm7 0h3.8v1.7h.1c.5-.9 1.8-1.9 3.8-1.9 4.1 0 4.9 2.4 4.9 5.6V21h-4v-5.4c0-1.3 0-3-1.9-3s-2.2 1.4-2.2 2.9V21h-4V9z"/>
                            </svg>
                        </a>
                    @endif

                    @if($safeHref($social['instagram'] ?? null))
                        <a href="{{ $safeHref($social['instagram']) }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex h-7 w-7 items-center justify-center rounded-lg hover:bg-white/10 transition"
                           aria-label="Instagram">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true">
                                <path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9A5.5 5.5 0 0 1 16.5 22h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9A3.5 3.5 0 0 0 20 16.5v-9A3.5 3.5 0 0 0 16.5 4h-9z"/>
                                <path d="M12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2.1A2.9 2.9 0 1 0 12 15a2.9 2.9 0 0 0 0-5.8z"/>
                                <path d="M17.7 6.3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                        </a>
                    @endif

                    @if($safeHref($social['facebook'] ?? null))
                        <a href="{{ $safeHref($social['facebook']) }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex h-7 w-7 items-center justify-center rounded-lg hover:bg-white/10 transition"
                           aria-label="Facebook">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true">
                                <path d="M22 12a10 10 0 1 0-11.6 9.9v-7H8v-3h2.4V9.6c0-2.4 1.4-3.7 3.6-3.7 1 0 2 .2 2 .2v2.2h-1.1c-1.1 0-1.4.7-1.4 1.4V12H16l-.4 3h-2.5v7A10 10 0 0 0 22 12z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Main navbar --}}
    <div class="max-w-7xl mx-auto px-4">
        <div class="h-16 flex items-center justify-between gap-4">
            {{-- Brand --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 min-w-0">
                @if(!empty($companyLogo))
                    <img
                        src="{{ str_starts_with($companyLogo, 'http') ? $companyLogo : asset('storage/' . ltrim(preg_replace('#^storage/#', '', $companyLogo), '/')) }}"
                        alt="{{ $appLocale==='en' ? $companyNameEn : $companyNameAr }}"
                        class="h-10 w-auto object-contain"
                        loading="lazy"
                    />
                @else
                    <div class="h-10 w-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black">
                        T
                    </div>
                @endif

                <div class="leading-tight min-w-0">
                    <div class="font-extrabold text-slate-900 text-sm sm:text-base truncate">
                        {{ $appLocale==='en' ? $companyNameEn : $companyNameAr }}
                    </div>

                    {{-- ✅ Tagline from Settings (control panel) --}}
                    <div class="hidden sm:block text-xs text-slate-500 truncate">
                        {{ $tagline }}
                    </div>
                </div>
            </a>

            {{-- Desktop menu (show from md not lg) --}}
            <nav class="hidden md:flex items-center gap-1">
                @foreach($headerMenuTree as $item)
                    @php
                        $hasChildren = isset($item->children) && $item->children->count() > 0;
                        $href = $itemUrl($item);
                    @endphp

                    @if($hasChildren)
                        <div class="relative group">
                            <button type="button"
                                    class="px-3 py-2 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition inline-flex items-center gap-2">
                                {{ $itemTitle($item) }}
                                <span class="text-slate-400">▾</span>
                            </button>

                            <div class="absolute {{ $isRtl ? 'right-0' : 'left-0' }} top-full mt-2 w-64
                                        opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-50">
                                <div class="bg-white border border-slate-200 rounded-2xl shadow-xl p-2">
                                    @foreach($item->children as $child)
                                        <a href="{{ $itemUrl($child) }}"{!! $itemTarget($child) !!}
                                           class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                            {{ $itemTitle($child) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $href }}"{!! $itemTarget($item) !!}
                           class="px-3 py-2 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                            {{ $itemTitle($item) }}
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Right actions --}}
            <div class="flex items-center gap-2">
                <a href="{{ $ctaUrl }}"
                   class="hidden sm:inline-flex items-center justify-center px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition">
                    {{ $ctaLabel }}
                </a>

                <button type="button"
                        onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
                        class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-100 transition"
                        aria-label="Open menu">
                    ☰
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobileMenu" class="md:hidden hidden pb-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-2 space-y-1">
                @foreach($headerMenuTree as $item)
                    @php
                        $hasChildren = isset($item->children) && $item->children->count() > 0;
                    @endphp

                    @if($hasChildren)
                        <details class="rounded-xl border border-slate-200 bg-white">
                            <summary class="px-3 py-2 cursor-pointer select-none font-extrabold text-slate-900">
                                {{ $itemTitle($item) }}
                            </summary>
                            <div class="px-2 pb-2">
                                @foreach($item->children as $child)
                                    <a href="{{ $itemUrl($child) }}"{!! $itemTarget($child) !!}
                                       class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                        {{ $itemTitle($child) }}
                                    </a>
                                @endforeach
                            </div>
                        </details>
                    @else
                        <a href="{{ $itemUrl($item) }}"{!! $itemTarget($item) !!}
                           class="block px-3 py-2 rounded-xl font-bold text-slate-700 hover:bg-slate-100 transition">
                            {{ $itemTitle($item) }}
                        </a>
                    @endif
                @endforeach

                <a href="{{ $ctaUrl }}"
                   class="mt-2 block text-center px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition">
                    {{ $ctaLabel }}
                </a>
            </div>
        </div>
    </div>
</header>
