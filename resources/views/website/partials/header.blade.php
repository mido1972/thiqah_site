@php
    use Illuminate\Support\Str;
    use App\Models\Menu;

    /* ================= Settings Helpers ================= */
    $ss = fn ($key, $default = null) => data_get($siteSettings ?? [], $key, $default);
    $appLocale = $appLocale ?? 'ar';

    $companyName = $appLocale === 'en'
        ? $ss('company_name_en', 'THIQA')
        : $ss('company_name', 'THIQA');

    $taglineRaw = $appLocale === 'en'
        ? ((string) $ss('header_tagline_en', (string) $ss('about_short_en', '')))
        : ((string) $ss('header_tagline_ar', (string) $ss('about_short', '')));

    $tagline = Str::limit(trim($taglineRaw), 80);
    if ($tagline === '') {
        $tagline = $appLocale === 'en' ? 'ERP • HR • Contracts' : 'حلول ERP و HR وإدارة العقود';
    }

    /* ================= Logo ================= */
    $logoRaw = (string) ($ss('logo_path') ?? '');
    $logoUrl = null;
    if ($logoRaw !== '') {
        $logoUrl = str_starts_with($logoRaw, 'http')
            ? $logoRaw
            : asset('storage/' . ltrim(preg_replace('#^storage/#', '', $logoRaw), '/'));
    }

    $ctaText = $appLocale === 'en' ? 'Request a Demo' : 'اطلب عرضًا';
    $ctaUrl  = url('/contact');

    /* ================= Load Menu (header) ================= */
    $menu = Menu::query()
        ->where('code', 'header')
        ->where('is_active', 1)
        ->with([
            'items' => fn ($q) => $q->whereNull('parent_id')->where('is_active', 1)->orderBy('order'),
            'items.page',
            'items.children' => fn ($q) => $q->where('is_active', 1)->orderBy('order'),
            'items.children.page',
            'items.children.children' => fn ($q) => $q->where('is_active', 1)->orderBy('order'),
            'items.children.children.page',
        ])
        ->first();

    $menuItems = $menu?->items ?? collect();

    /* ================= Menu Helpers ================= */
    $itemTitle = fn ($item) => $appLocale === 'en'
        ? ($item->title_en ?: $item->title_ar)
        : ($item->title_ar ?: $item->title_en);

    $normalizeInternalUrl = function (string $u) {
        $u = trim($u);
        if ($u === '') return '#';

        // absolute
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://')) {
            return $u;
        }

        // anchor / mailto / tel
        if (str_starts_with($u, '#') || str_starts_with($u, 'mailto:') || str_starts_with($u, 'tel:')) {
            return $u;
        }

        // internal path
        return url($u);
    };

    /**
     * ✅ FIXED:
     * - type=page له أولوية
     * - لو url = "#goals" نلحقه برابط الصفحة => /about#goals
     * - type=url يرجّع url كما هو
     */
    $itemUrl = function ($item) use ($normalizeInternalUrl) {
        $type = (string) ($item->type ?? '');

        // 1) route_name (اختياري)
        if (!empty($item->route_name) && \Illuminate\Support\Facades\Route::has($item->route_name)) {
            try { return route($item->route_name); } catch (\Throwable $e) {}
        }

        // 2) type = page (الأهم) أو page_id موجود
        if ($type === 'page' || !empty($item->page_id)) {
            if (isset($item->page) && $item->page) {
                $p = $item->page;

                // جرّب أشهر الحقول بدون افتراض
                $pageUrl  = $p->url  ?? null;
                $pagePath = $p->path ?? null;
                $pageSlug = $p->slug ?? null;

                $base = null;

                if (!empty($pageUrl)) {
                    $base = (string) $pageUrl;
                } elseif (!empty($pagePath)) {
                    $base = (string) $pagePath;
                } elseif (!empty($pageSlug)) {
                    $base = '/' . ltrim((string) $pageSlug, '/');
                }

                if (!empty($base)) {
                    $base = $normalizeInternalUrl($base);

                    // ✅ لو عندنا fragment مثل #goals نخليه /about#goals
                    $u = trim((string) ($item->url ?? ''));
                    if ($u !== '' && str_starts_with($u, '#')) {
                        return $base . $u;
                    }

                    // لو url مش fragment (مثلاً /custom) نخليه override اختياري
                    if ($u !== '' && !str_starts_with($u, '#')) {
                        return $normalizeInternalUrl($u);
                    }

                    return $base;
                }

                // fallback آمن
                return $normalizeInternalUrl('/pages/' . $p->id);
            }

            // page_id موجود لكن page مش متحمّلة
            return '#';
        }

        // 3) type = url
        if ($type === 'url') {
            return !empty($item->url) ? $normalizeInternalUrl((string) $item->url) : '#';
        }

        // 4) لو النوع فاضي لكن url موجود
        if (!empty($item->url)) {
            return $normalizeInternalUrl((string) $item->url);
        }

        return '#';
    };

    $itemTarget = function ($item) {
        return !empty($item->open_in_new_tab) ? ' target="_blank" rel="noopener noreferrer"' : '';
    };

    $isActiveUrl = function ($url) {
        if (!$url || $url === '#') return false;
        $path = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        if ($path === '') return request()->is('/');
        return request()->is($path) || request()->is($path . '/*');
    };
@endphp

<header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/85 backdrop-blur">
    <div class="container-site">
        <div class="flex items-center justify-between gap-4 py-2">

            {{-- Brand --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 min-w-0">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $companyName }}"
                         class="h-11 sm:h-12 md:h-14 w-auto object-contain" loading="lazy" />
                @else
                    <div class="h-11 w-11 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black">T</div>
                @endif

                <div class="min-w-0 leading-tight">
                    <div class="text-sm sm:text-base font-extrabold text-slate-900 truncate">{{ $companyName }}</div>
                    <div class="hidden sm:block text-xs text-slate-500 truncate">{{ $tagline }}</div>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-1">
                @foreach($menuItems as $item)
                    @php
                        $hasChildren = $item->children && $item->children->count();
                        $href = $itemUrl($item);
                        $active = $isActiveUrl($href);
                    @endphp

                    @if($hasChildren)
                        <div class="relative group">
                            {{-- Dropdown toggle --}}
                            <button type="button"
                                class="px-3 py-2 rounded-xl text-sm font-bold transition inline-flex items-center gap-2
                                       {{ $active ? 'bg-slate-100 text-slate-900' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                {{ $itemTitle($item) }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 1 1 1.06 1.06l-4.24 4.24a.75.75 0 0 1-1.06 0L5.21 8.29a.75.75 0 0 1 .02-1.08z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            {{-- Dropdown --}}
                            <div
                                class="absolute {{ $appLocale==='en' ? 'left-0' : 'right-0' }} top-full w-64 pt-2 z-50
                                       opacity-0 invisible group-hover:opacity-100 group-hover:visible
                                       pointer-events-none group-hover:pointer-events-auto transition"
                            >
                                <div class="rounded-2xl border border-slate-200 bg-white shadow-xl p-2">
                                    @foreach($item->children as $child)
                                        @php
                                            $childHas = $child->children && $child->children->count();
                                            $childHref = $itemUrl($child);
                                        @endphp

                                        @if($childHas)
                                            <div class="relative group/child">
                                                <a href="{{ $childHref }}"{!! $itemTarget($child) !!}
                                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-extrabold text-slate-800 hover:bg-slate-100">
                                                    <span>{{ $itemTitle($child) }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.24 4.24a.75.75 0 0 1 0 1.06l-4.24 4.24a.75.75 0 0 1-1.08.02z" clip-rule="evenodd"/>
                                                    </svg>
                                                </a>

                                                <div
                                                    class="absolute top-0 {{ $appLocale==='en' ? 'left-full pl-2' : 'right-full pr-2' }} w-64
                                                           opacity-0 invisible group-hover/child:opacity-100 group-hover/child:visible
                                                           pointer-events-none group-hover/child:pointer-events-auto transition"
                                                >
                                                    <div class="rounded-2xl border border-slate-200 bg-white shadow-xl p-2">
                                                        @foreach($child->children as $grand)
                                                            <a href="{{ $itemUrl($grand) }}"{!! $itemTarget($grand) !!}
                                                               class="block px-3 py-2 rounded-xl text-[13px] font-semibold
                                                                      text-slate-600 hover:text-slate-900 hover:bg-slate-100
                                                                      {{ $appLocale==='en' ? 'border-l-2 pl-4' : 'border-r-2 pr-4' }}
                                                                      border-transparent hover:border-slate-300">
                                                                {{ $itemTitle($grand) }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ $childHref }}"{!! $itemTarget($child) !!}
                                               class="block px-3 py-2 rounded-xl text-[13px] font-semibold
                                                      text-slate-600 hover:text-slate-900 hover:bg-slate-100
                                                      {{ $appLocale==='en' ? 'border-l-2 pl-4' : 'border-r-2 pr-4' }}
                                                      border-transparent hover:border-slate-300">
                                                {{ $itemTitle($child) }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $href }}"{!! $itemTarget($item) !!}
                           class="px-3 py-2 rounded-xl text-sm font-bold transition
                                  {{ $active ? 'bg-slate-100 text-slate-900' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                            {{ $itemTitle($item) }}
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <a href="{{ $ctaUrl }}" class="hidden sm:inline-flex btn-primary">{{ $ctaText }}</a>

                <button type="button"
                        onclick="document.getElementById('mobileNav').classList.toggle('hidden');"
                        class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-100 transition"
                        aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>

        {{-- Mobile Nav (accordion) --}}
        <div id="mobileNav" class="md:hidden hidden pb-4">
            <div class="flex flex-col gap-1">
                @foreach($menuItems as $item)
                    @php
                        $hasChildren = $item->children && $item->children->count();
                        $href = $itemUrl($item);
                    @endphp

                    @if($hasChildren)
                        <details class="rounded-xl border border-slate-200 bg-white">
                            <summary class="px-3 py-2 text-sm font-extrabold text-slate-900 cursor-pointer select-none">
                                {{ $itemTitle($item) }}
                            </summary>

                            <div class="px-2 pb-2">
                                @foreach($item->children as $child)
                                    @php
                                        $childHas = $child->children && $child->children->count();
                                        $childHref = $itemUrl($child);
                                    @endphp

                                    @if($childHas)
                                        <details class="mt-1 rounded-xl border border-slate-200 bg-white">
                                            <summary class="px-3 py-2 text-sm font-bold text-slate-800 cursor-pointer select-none">
                                                {{ $itemTitle($child) }}
                                            </summary>
                                            <div class="px-2 pb-2">
                                                @foreach($child->children as $grand)
                                                    <a href="{{ $itemUrl($grand) }}"{!! $itemTarget($grand) !!}
                                                       class="block px-3 py-2 rounded-xl text-[13px] font-semibold
                                                              text-slate-600 hover:text-slate-900 hover:bg-slate-100
                                                              {{ $appLocale==='en' ? 'border-l-2 pl-4' : 'border-r-2 pr-4' }}
                                                              border-transparent hover:border-slate-300">
                                                        {{ $itemTitle($grand) }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </details>
                                    @else
                                        <a href="{{ $childHref }}"{!! $itemTarget($child) !!}
                                           class="mt-1 block px-3 py-2 rounded-xl text-[13px] font-semibold
                                                  text-slate-600 hover:text-slate-900 hover:bg-slate-100
                                                  {{ $appLocale==='en' ? 'border-l-2 pl-4' : 'border-r-2 pr-4' }}
                                                  border-transparent hover:border-slate-300">
                                            {{ $itemTitle($child) }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </details>
                    @else
                        <a href="{{ $href }}"{!! $itemTarget($item) !!}
                           class="px-3 py-2 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100 transition">
                            {{ $itemTitle($item) }}
                        </a>
                    @endif
                @endforeach

                <a href="{{ $ctaUrl }}" class="mt-2 btn-primary text-center">{{ $ctaText }}</a>
            </div>
        </div>

    </div>
</header>
