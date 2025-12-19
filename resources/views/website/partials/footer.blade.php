@php
    use Illuminate\Support\Str;
    use App\Models\Menu;

    /* ================= Settings Helpers ================= */
    $ss = fn ($key, $default = null) => data_get($siteSettings ?? [], $key, $default);

    $appLocale = $appLocale ?? request()->query('lang', 'ar');
    $appLocale = strtolower($appLocale) === 'en' ? 'en' : 'ar';

    $companyName = $appLocale === 'en'
        ? ($ss('company_name_en', 'THIQA'))
        : ($ss('company_name', 'THIQA'));

    $aboutShort = trim((string) ($appLocale === 'en' ? ($ss('about_short_en') ?? '') : ($ss('about_short') ?? '')));

    $phone    = (string) ($ss('phone') ?? '');
    $whatsapp = (string) ($ss('whatsapp') ?? '');
    $email    = (string) ($ss('email') ?? '');
    $address  = trim((string) ($appLocale === 'en' ? ($ss('address_en') ?? '') : ($ss('address') ?? '')));

    // Background image (اختياري)
    $footerBg = (string) ($ss('footer_bg_path') ?? '');
    $footerBgUrl = null;
    if ($footerBg !== '') {
        $footerBgUrl = str_starts_with($footerBg, 'http')
            ? $footerBg
            : asset('storage/' . ltrim(preg_replace('#^storage/#', '', $footerBg), '/'));
    }

    $year = date('Y');

    $social = [
        'facebook'  => $ss('facebook'),
        'linkedin'  => $ss('linkedin'),
        'twitter'   => $ss('twitter'),
        'instagram' => $ss('instagram'),
        'youtube'   => $ss('youtube'),
        'tiktok'    => $ss('tiktok'),
    ];

    $waDigits = $whatsapp ? preg_replace('/\D+/', '', $whatsapp) : '';
    $waLink = $waDigits ? 'https://wa.me/' . $waDigits : '';

    $icons = [
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.02H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.25.2 2.25.2v2.48h-1.27c-1.25 0-1.64.78-1.64 1.58v1.9h2.79l-.45 2.91h-2.34V22c4.78-.75 8.44-4.91 8.44-9.93Z"/></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.95v5.66H9.36V9h3.4v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.12 20.45H3.56V9h3.56v11.45Z"/></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-6.78 7.75L23 22h-6.2l-4.86-7.52L5.34 22H2l7.3-8.35L1.7 2H8l4.4 6.95L18.9 2Zm-1.09 18.1h1.72L7.06 3.8H5.22l12.59 16.3Z"/></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9A5.5 5.5 0 0 1 16.5 22h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm9 2h-9A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9A3.5 3.5 0 0 0 20 16.5v-9A3.5 3.5 0 0 0 16.5 4ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm6.2-.9a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0Z"/></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21.58 7.19a2.5 2.5 0 0 0-1.76-1.77C18.27 5 12 5 12 5s-6.27 0-7.82.42A2.5 2.5 0 0 0 2.42 7.2 26.7 26.7 0 0 0 2 12c0 1.6.14 3.2.42 4.81a2.5 2.5 0 0 0 1.76 1.77C5.73 19 12 19 12 19s6.27 0 7.82-.42a2.5 2.5 0 0 0 1.76-1.77c.28-1.6.42-3.2.42-4.81s-.14-3.2-.42-4.81ZM10 15V9l6 3-6 3Z"/></svg>',
        'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 2h-3v13.2a3.3 3.3 0 1 1-2.25-3.12V9.02a6.3 6.3 0 1 0 5.25 6.18V8.7c1.12.8 2.5 1.3 4 1.3v-3c-2.21 0-4-1.79-4-4Z"/></svg>',
        'whatsapp' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2a9.86 9.86 0 0 0-9.9 9.8c0 1.73.47 3.41 1.36 4.88L2 22l5.47-1.43a9.93 9.93 0 0 0 4.57 1.12h.01a9.86 9.86 0 0 0 9.9-9.8A9.86 9.86 0 0 0 12.04 2Zm5.78 14.18c-.24.67-1.2 1.24-1.66 1.3-.43.06-.98.08-1.58-.1-.37-.12-.85-.28-1.47-.55-2.59-1.12-4.28-3.73-4.41-3.9-.13-.18-1.05-1.39-1.05-2.65 0-1.26.66-1.88.9-2.14.24-.26.53-.33.7-.33.18 0 .35 0 .5.01.16.01.37-.06.58.44.24.58.8 2 .87 2.14.07.14.11.3.02.48-.09.18-.13.3-.26.46-.13.16-.28.35-.4.47-.13.13-.26.28-.11.54.15.26.67 1.1 1.44 1.78.99.88 1.82 1.16 2.08 1.29.26.13.41.11.56-.07.15-.18.64-.75.81-1.01.17-.26.34-.22.58-.13.24.09 1.53.72 1.79.85.26.13.43.2.5.31.07.11.07.65-.17 1.32Z"/></svg>',
    ];

    $socialLinks = [];
    foreach ($social as $k => $u) {
        if (!empty($u) && isset($icons[$k])) $socialLinks[$k] = $u;
    }
    if ($waLink) $socialLinks = ['whatsapp' => $waLink] + $socialLinks;

    /* ================= Load Footer Menu ================= */
    $footerMenu = Menu::query()
        ->where('code', 'footer')
        ->where('is_active', 1)
        ->with([
            'items' => fn ($q) => $q->whereNull('parent_id')->where('is_active', 1)->orderBy('order'),
            'items.page',
            'items.children' => fn ($q) => $q->where('is_active', 1)->orderBy('order'),
            'items.children.page',
        ])
        ->first();

    $columns = $footerMenu?->items ?? collect();

    $itemTitle = fn ($item) => $appLocale === 'en'
        ? ($item->title_en ?: $item->title_ar)
        : ($item->title_ar ?: $item->title_en);

    $normalizeInternalUrl = function (string $u) {
        $u = trim($u);
        if ($u === '') return '#';
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://')) return $u;
        if (str_starts_with($u, '#') || str_starts_with($u, 'mailto:') || str_starts_with($u, 'tel:')) return $u;
        return url($u);
    };

    $itemUrl = function ($item) use ($normalizeInternalUrl) {
        $type = (string) ($item->type ?? '');

        if ($type === 'page' || !empty($item->page_id)) {
            if (!empty($item->page)) {
                $p = $item->page;
                $base = null;

                if (!empty($p->url))        $base = (string) $p->url;
                elseif (!empty($p->path))   $base = (string) $p->path;
                elseif (!empty($p->slug))   $base = '/' . ltrim((string) $p->slug, '/');

                if ($base) {
                    $base = $normalizeInternalUrl($base);
                    $u = trim((string) ($item->url ?? ''));

                    if ($u !== '' && str_starts_with($u, '#')) return $base . $u;
                    if ($u !== '' && !str_starts_with($u, '#')) return $normalizeInternalUrl($u);

                    return $base;
                }
            }
            return '#';
        }

        if ($type === 'url') {
            return !empty($item->url) ? $normalizeInternalUrl((string) $item->url) : '#';
        }

        return !empty($item->url) ? $normalizeInternalUrl((string) $item->url) : '#';
    };
@endphp

<footer class="relative mt-12 text-white overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0">
        @if($footerBgUrl)
            <img src="{{ $footerBgUrl }}" alt="Footer background" class="h-full w-full object-cover opacity-25" />
        @endif
        <div class="absolute inset-0 bg-slate-950/90"></div>
    </div>

    <div class="relative container-site py-12">
        <div class="grid gap-10 lg:grid-cols-12 items-start">

            {{-- Contact --}}
            <div class="lg:col-span-4">
                <div class="text-lg font-black tracking-tight">{{ $companyName }}</div>

                @if($aboutShort)
                    <p class="mt-3 text-sm leading-7 text-slate-200/85">
                        {{ Str::limit($aboutShort, 240) }}
                    </p>
                @endif

                <div class="mt-6 space-y-3 text-sm text-slate-200/85">
                    @if($address)
                        <div class="flex gap-2">
                            <span class="text-slate-200/60">•</span>
                            <span>{{ $address }}</span>
                        </div>
                    @endif

                    @if($phone)
                        <div class="flex gap-2">
                            <span class="text-slate-200/60">•</span>
                            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="hover:text-white transition">{{ $phone }}</a>
                        </div>
                    @endif

                    @if($email)
                        <div class="flex gap-2">
                            <span class="text-slate-200/60">•</span>
                            <a href="mailto:{{ $email }}" class="hover:text-white transition break-all">{{ $email }}</a>
                        </div>
                    @endif
                </div>

                {{-- Social centered like screenshot --}}
                @if(count($socialLinks))
                    <div class="mt-6 flex justify-center lg:justify-start">
                        <div class="flex flex-wrap gap-2">
                            @foreach($socialLinks as $key => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener"
                                   class="h-10 w-10 rounded-full bg-white/10 border border-white/10
                                          hover:bg-white/15 hover:border-white/20 transition
                                          flex items-center justify-center"
                                   aria-label="{{ ucfirst($key) }}">
                                    <span class="h-5 w-5 text-white">{!! $icons[$key] !!}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer Menu Columns --}}
            <div class="lg:col-span-8">
                <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($columns as $col)
                        @php $links = $col->children ?? collect(); @endphp

                        <div>
                            <div class="text-base font-extrabold">
                                {{ $itemTitle($col) }}
                            </div>
                            <div class="mt-3 h-px w-full bg-white/10"></div>

                            @if($links->count())
                                <ul class="mt-4 text-sm text-slate-200/85">
                                    @foreach($links as $link)
                                        <li class="py-3 {{ !$loop->last ? 'border-b border-white/10' : '' }}">
                                            <a href="{{ $itemUrl($link) }}" class="hover:text-white transition">
                                                {{ $itemTitle($link) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mt-4 text-sm text-slate-200/70">
                                    {{ $appLocale==='en' ? 'Add links under this section from the dashboard.' : 'أضف روابط داخل هذا القسم من لوحة التحكم.' }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3 text-slate-200/80 text-sm">
                            {{ $appLocale==='en'
                                ? 'Create a menu with code "footer" and add parent items (sections) and children (links).'
                                : 'أنشئ قائمة (Menu) بكود "footer" وأضف عناصر رئيسية (أقسام) وتحتها عناصر فرعية (روابط).' }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-10 border-t border-white/10 pt-5 flex flex-col md:flex-row gap-2 md:items-center md:justify-between text-xs text-slate-200/70">
            <div>© {{ $year }} {{ $companyName }} — {{ $appLocale==='en' ? 'All rights reserved.' : 'جميع الحقوق محفوظة.' }}</div>
            <div class="text-slate-200/60">{{ $appLocale==='en' ? 'Built with Laravel + Filament.' : 'مبني باستخدام Laravel + Filament.' }}</div>
        </div>
    </div>
</footer>
