@php
    use Illuminate\Support\Str;

    $ss = fn ($key, $default = null) => data_get($siteSettings ?? [], $key, $default);

    $appLocale = $appLocale ?? request()->query('lang', 'ar');
    $appLocale = strtolower($appLocale) === 'en' ? 'en' : 'ar';

    $companyName = $appLocale === 'en'
        ? ($ss('company_name_en', 'THIQA'))
        : ($ss('company_name', 'THIQA'));

    $aboutShort = (string) ($ss('about_short') ?? '');
    $phone    = (string) ($ss('phone') ?? '');
    $whatsapp = (string) ($ss('whatsapp') ?? '');
    $email    = (string) ($ss('email') ?? '');

    $social = [
        'facebook'  => $ss('facebook'),
        'linkedin'  => $ss('linkedin'),
        'twitter'   => $ss('twitter'),
        'instagram' => $ss('instagram'),
        'youtube'   => $ss('youtube'),
        'tiktok'    => $ss('tiktok'),
    ];

    $waLink = '';
    $waDigits = '';
    if ($whatsapp) {
        $waDigits = preg_replace('/\D+/', '', $whatsapp);
        $waLink = $waDigits ? 'https://wa.me/' . $waDigits : '';
    }

    $year = date('Y');

    $icons = [
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.02H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.25.2 2.25.2v2.48h-1.27c-1.25 0-1.64.78-1.64 1.58v1.9h2.79l-.45 2.91h-2.34V22c4.78-.75 8.44-4.91 8.44-9.93Z"/></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.95v5.66H9.36V9h3.4v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.12 20.45H3.56V9h3.56v11.45Z"/></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-6.78 7.75L23 22h-6.2l-4.86-7.52L5.34 22H2l7.3-8.35L1.7 2H8l4.4 6.95L18.9 2Zm-1.09 18.1h1.72L7.06 3.8H5.22l12.59 16.3Z"/></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9A5.5 5.5 0 0 1 16.5 22h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm9 2h-9A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9A3.5 3.5 0 0 0 20 16.5v-9A3.5 3.5 0 0 0 16.5 4ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm6.2-.9a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0Z"/></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21.58 7.19a2.5 2.5 0 0 0-1.76-1.77C18.27 5 12 5 12 5s-6.27 0-7.82.42A2.5 2.5 0 0 0 2.42 7.2 26.7 26.7 0 0 0 2 12c0 1.6.14 3.2.42 4.81a2.5 2.5 0 0 0 1.76 1.77C5.73 19 12 19 12 19s6.27 0 7.82-.42a2.5 2.5 0 0 0 1.76-1.77c.28-1.6.42-3.2.42-4.81s-.14-3.2-.42-4.81ZM10 15V9l6 3-6 3Z"/></svg>',
        'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 2h-3v13.2a3.3 3.3 0 1 1-2.25-3.12V9.02a6.3 6.3 0 1 0 5.25 6.18V8.7c1.12.8 2.5 1.3 4 1.3v-3c-2.21 0-4-1.79-4-4Z"/></svg>',
        'whatsapp' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2a9.86 9.86 0 0 0-9.9 9.8c0 1.73.47 3.41 1.36 4.88L2 22l5.47-1.43a9.93 9.93 0 0 0 4.57 1.12h.01a9.86 9.86 0 0 0 9.9-9.8A9.86 9.86 0 0 0 12.04 2Zm5.78 14.18c-.24.67-1.2 1.24-1.66 1.3-.43.06-.98.08-1.58-.1-.37-.12-.85-.28-1.47-.55-2.59-1.12-4.28-3.73-4.41-3.9-.13-.18-1.05-1.39-1.05-2.65 0-1.26.66-1.88.9-2.14.24-.26.53-.33.7-.33.18 0 .35 0 .5.01.16.01.37-.06.58.44.24.58.8 2 .87 2.14.07.14.11.3.02.48-.09.18-.13.3-.26.46-.13.16-.28.35-.4.47-.13.13-.26.28-.11.54.15.26.67 1.1 1.44 1.78.99.88 1.82 1.16 2.08 1.29.26.13.41.11.56-.07.15-.18.64-.75.81-1.01.17-.26.34-.22.58-.13.24.09 1.53.72 1.79.85.26.13.43.2.5.31.07.11.07.65-.17 1.32Z"/></svg>',
    ];

    $brandLine = $appLocale === 'en'
        ? 'ERP, HR & Contracting Solutions'
        : 'حلول ERP و HR وإدارة العقود';

    $logoRaw = (string) ($ss('logo_path') ?? '');
    $logoUrl = null;
    if ($logoRaw !== '') {
        if (str_starts_with($logoRaw, 'http://') || str_starts_with($logoRaw, 'https://')) {
            $logoUrl = $logoRaw;
        } else {
            $logoRaw = ltrim($logoRaw, '/');
            $logoRaw = preg_replace('#^storage/#', '', $logoRaw);
            $logoUrl = asset('storage/' . $logoRaw);
        }
    }

    // ✅ filter only valid social links (avoid empty icons)
    $socialLinks = [];
    foreach ($social as $key => $url) {
        if (!empty($url) && isset($icons[$key])) {
            $socialLinks[$key] = $url;
        }
    }
@endphp

<footer class="mt-10 border-t border-slate-200 bg-white">
    {{-- ✅ قللنا padding العام أكثر --}}
    <div class="container-site py-5">

        {{-- ✅ قللنا gap والمسافات + منع أي stretch يسبب فراغ --}}
        <div class="grid gap-5 md:grid-cols-12 items-start">

            {{-- Brand --}}
            <div class="md:col-span-5">
                <div class="flex items-start gap-3">
                    <div class="shrink-0">
                        @if(!empty($logoUrl))
                            <img
                                src="{{ $logoUrl }}"
                                alt="{{ $companyName }}"
                                class="h-11 w-11 rounded-2xl object-contain border border-slate-200 bg-white p-1"
                                loading="lazy"
                            />
                        @else
                            <div class="h-11 w-11 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black">
                                {{ strtoupper(mb_substr($companyName, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <div class="text-[15px] font-black text-slate-900 leading-tight">
                            {{ $companyName }}
                        </div>
                        <div class="text-[12px] text-slate-500 mt-1">
                            {{ $brandLine }}
                        </div>

                        <p class="text-[13px] leading-6 text-slate-600 mt-2">
                            {{ $aboutShort ?: ($appLocale==='en'
                                ? 'We build scalable business systems with a clean modern UI.'
                                : 'نطوّر أنظمة أعمال قابلة للتوسع بواجهة حديثة ونظيفة.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Links --}}
            <div class="md:col-span-7">
                <div class="grid gap-5 sm:grid-cols-3">

                    <div>
                        <div class="text-sm font-extrabold text-slate-900">
                            {{ $appLocale==='en' ? 'Company' : 'الشركة' }}
                        </div>
                        <ul class="mt-2 space-y-1.5 text-[13px]">
                            <li><a class="text-slate-600 hover:text-slate-900" href="{{ url('/about') }}">{{ $appLocale==='en' ? 'About us' : 'من نحن' }}</a></li>
                            <li><a class="text-slate-600 hover:text-slate-900" href="{{ url('/contact') }}">{{ $appLocale==='en' ? 'Contact' : 'تواصل' }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <div class="text-sm font-extrabold text-slate-900">
                            {{ $appLocale==='en' ? 'Services' : 'الخدمات' }}
                        </div>
                        <ul class="mt-2 space-y-1.5 text-[13px]">
                            <li><a class="text-slate-600 hover:text-slate-900" href="{{ url('/services') }}">{{ $appLocale==='en' ? 'All services' : 'كل الخدمات' }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <div class="text-sm font-extrabold text-slate-900">
                            {{ $appLocale==='en' ? 'Contact' : 'التواصل' }}
                        </div>

                        <ul class="mt-2 space-y-2 text-[13px] text-slate-600">
                            @if($phone)
                                <li>
                                    <span class="font-extrabold">{{ $appLocale==='en' ? 'Phone' : 'الهاتف' }}:</span>
                                    <span class="ms-1">{{ $phone }}</span>
                                </li>
                            @endif

                            @if($whatsapp)
                                <li class="flex items-center gap-2">
                                    <a href="{{ $waLink ?: '#' }}" target="_blank" rel="noopener"
                                       class="h-8 w-8 rounded-xl border border-slate-200 text-slate-600 hover:text-green-600 hover:border-green-300 hover:bg-green-50 transition flex items-center justify-center"
                                       aria-label="WhatsApp">
                                        <span class="h-4 w-4">{!! $icons['whatsapp'] !!}</span>
                                    </a>

                                    <div class="min-w-0">
                                        <span class="font-extrabold">WhatsApp:</span>
                                        @if($waLink)
                                            <a href="{{ $waLink }}" target="_blank" rel="noopener" class="ms-1 text-slate-700 hover:text-slate-900">
                                                {{ $whatsapp }}
                                            </a>
                                        @else
                                            <span class="ms-1">{{ $whatsapp }}</span>
                                        @endif
                                    </div>
                                </li>
                            @endif

                            @if($email)
                                <li class="break-words">
                                    <span class="font-extrabold">{{ $appLocale==='en' ? 'Email' : 'البريد' }}:</span>
                                    <span class="ms-1">{{ $email }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>

        </div>

        {{-- ✅ السوشيال آخر حاجة ووسط + مسافة صغيرة جدًا --}}
        @if(count($socialLinks))
            <div class="mt-3 flex justify-center">
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach($socialLinks as $key => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener"
                           class="h-9 w-9 rounded-xl border border-slate-200 text-slate-600 hover:text-orange-600 hover:border-orange-300 hover:bg-orange-50 transition flex items-center justify-center"
                           aria-label="{{ ucfirst($key) }}">
                            <span class="h-4 w-4">{!! $icons[$key] !!}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ✅ Bottom أقرب + خط أخف --}}
        <div class="mt-3 border-t border-slate-200 pt-2.5 flex flex-col md:flex-row gap-1 md:justify-between text-[11px] text-slate-500">
            <div>© {{ $year }} {{ $companyName }} — {{ $appLocale==='en' ? 'All rights reserved.' : 'جميع الحقوق محفوظة.' }}</div>
            <div>{{ $appLocale==='en' ? 'Built with Laravel + Filament.' : 'مبني باستخدام Laravel + Filament.' }}</div>
        </div>

    </div>
</footer>
