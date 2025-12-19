@php
    /** @var \App\Models\HeroSlider|null $slider */
    $slider = $heroSlider ?? null;

    // ✅ Use query lang (your project standard)
    $locale = $locale ?? (request()->query('lang', 'ar') === 'en' ? 'en' : 'ar');
    $isEn = $locale === 'en';

    $slides = $slider?->slides?->where('is_active', true)->values() ?? collect();

    // ✅ Helper: pick localized value with fallback
    $pick = function ($ar, $en) use ($isEn) {
        $ar = is_string($ar) ? trim($ar) : $ar;
        $en = is_string($en) ? trim($en) : $en;

        if ($isEn) return $en ?: $ar;
        return $ar ?: $en;
    };

    // ✅ Helper: append lang to internal links
    $withLang = function (?string $url) use ($locale) {
        $url = trim((string) $url);
        if ($url === '') return null;

        // absolute / special schemes
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) return $url;
        if (str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:') || str_starts_with($url, '#')) return $url;

        // internal
        $full = url('/' . ltrim($url, '/'));
        $sep = str_contains($full, '?') ? '&' : '?';
        return $full . $sep . 'lang=' . $locale;
    };

    // ✅ Slide interval
    $intervalMs = 6500;
@endphp

@if($slider && $slides->count())
<section class="relative w-full overflow-hidden">
    <div class="relative">

        {{-- Slides --}}
        @foreach($slides as $index => $slide)
            @php
                $isFirst = $index === 0;

                $title    = $pick($slide->title_ar ?? null, $slide->title_en ?? null);
                $subtitle = $pick($slide->subtitle_ar ?? null, $slide->subtitle_en ?? null);
                $content  = $pick($slide->content_ar ?? null, $slide->content_en ?? null);

                $ctaLabel = $pick($slide->cta_label_ar ?? null, $slide->cta_label_en ?? null);
                $ctaUrl   = $withLang($slide->cta_url ?? null);

                $bg = trim((string) ($slide->main_image ?? ''));
                $bgUrl = $bg !== '' ? asset('storage/' . ltrim($bg, '/')) : null;

                $overlays = is_array($slide->overlay_images) ? array_values(array_filter($slide->overlay_images)) : [];
                $overlays = array_slice($overlays, 0, 4); // max 4 overlays
            @endphp

            <div
                class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $isFirst ? 'opacity-100 relative' : 'opacity-0 pointer-events-none' }}"
                data-slide="{{ $index }}"
                aria-hidden="{{ $isFirst ? 'false' : 'true' }}"
            >
                {{-- Background --}}
                @if($bgUrl)
                    <div class="absolute inset-0 bg-cover bg-center"
                         style="background-image:url('{{ $bgUrl }}')"></div>
                @else
                    <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950"></div>
                @endif

                {{-- Dark overlay --}}
                <div class="absolute inset-0 bg-slate-950/70"></div>

                {{-- Content --}}
                <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 md:py-32 text-white">
                    <div class="max-w-2xl">

                        @if($title)
                            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4">
                                {{ $title }}
                            </h1>
                        @endif

                        @if($subtitle)
                            <p class="text-base md:text-lg opacity-90 mb-3">
                                {{ $subtitle }}
                            </p>
                        @endif

                        @if($content)
                            <div class="prose prose-invert max-w-none mb-6">
                                {!! $content !!}
                            </div>
                        @endif

                        @if($ctaUrl && $ctaLabel)
                            <a href="{{ $ctaUrl }}"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl
                                      bg-orange-500 hover:bg-orange-600 text-white font-extrabold transition">
                                {{ $ctaLabel }}
                                <span aria-hidden="true">{{ $isEn ? '→' : '←' }}</span>
                            </a>
                        @endif

                    </div>
                </div>

                {{-- Overlay images (up to 4) --}}
                @if(count($overlays))
                    <div class="hidden lg:block absolute inset-0 z-10 pointer-events-none">
                        @foreach($overlays as $k => $img)
                            @php
                                $imgUrl = asset('storage/' . ltrim($img, '/'));
                                // positions preset (nice layout)
                                $pos = [
                                    'bottom-10 right-10',
                                    'bottom-16 right-56',
                                    'top-24 right-12',
                                    'top-28 right-64',
                                ][$k] ?? 'bottom-10 right-10';
                            @endphp
                            <img
                                src="{{ $imgUrl }}"
                                class="absolute {{ $pos }} max-h-40 opacity-95 drop-shadow-xl"
                                alt=""
                                loading="lazy"
                            >
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        {{-- Dots --}}
        @if($slides->count() > 1)
            <div class="absolute bottom-6 left-0 right-0 z-20">
                <div class="max-w-7xl mx-auto px-6 flex items-center gap-2 {{ $isEn ? 'justify-start' : 'justify-end' }}">
                    @foreach($slides as $i => $s)
                        <button
                            type="button"
                            class="hero-dot h-2.5 w-2.5 rounded-full bg-white/40 hover:bg-white/70 transition"
                            data-dot="{{ $i }}"
                            aria-label="Slide {{ $i + 1 }}"
                        ></button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Arrows --}}
        @if($slides->count() > 1)
            <button type="button"
                    class="hero-prev hidden md:flex absolute top-1/2 -translate-y-1/2 z-20
                           {{ $isEn ? 'left-6' : 'right-6' }}
                           h-11 w-11 items-center justify-center rounded-2xl bg-white/10 hover:bg-white/20
                           border border-white/15 text-white transition"
                    aria-label="Previous slide">
                {{ $isEn ? '←' : '→' }}
            </button>

            <button type="button"
                    class="hero-next hidden md:flex absolute top-1/2 -translate-y-1/2 z-20
                           {{ $isEn ? 'left-20' : 'right-20' }}
                           h-11 w-11 items-center justify-center rounded-2xl bg-white/10 hover:bg-white/20
                           border border-white/15 text-white transition"
                    aria-label="Next slide">
                {{ $isEn ? '→' : '←' }}
            </button>
        @endif

    </div>

    {{-- Minimal JS slider (no libraries) --}}
    @if($slides->count() > 1)
        <script>
            (function () {
                const root = document.currentScript.closest('section');
                if (!root) return;

                const slides = Array.from(root.querySelectorAll('.hero-slide'));
                const dots = Array.from(root.querySelectorAll('.hero-dot'));
                const btnPrev = root.querySelector('.hero-prev');
                const btnNext = root.querySelector('.hero-next');

                let i = 0;
                let timer = null;

                function show(idx) {
                    i = (idx + slides.length) % slides.length;
                    slides.forEach((el, n) => {
                        const active = n === i;
                        el.classList.toggle('opacity-100', active);
                        el.classList.toggle('opacity-0', !active);
                        el.classList.toggle('relative', active);
                        el.classList.toggle('absolute', !active);
                        el.classList.toggle('pointer-events-none', !active);
                        el.setAttribute('aria-hidden', active ? 'false' : 'true');
                    });

                    dots.forEach((d, n) => {
                        d.classList.toggle('bg-white', n === i);
                        d.classList.toggle('bg-white/40', n !== i);
                    });
                }

                function next() { show(i + 1); }
                function prev() { show(i - 1); }

                function start() {
                    stop();
                    timer = setInterval(next, {{ $intervalMs }});
                }
                function stop() {
                    if (timer) clearInterval(timer);
                    timer = null;
                }

                // init
                show(0);
                start();

                dots.forEach((d) => {
                    d.addEventListener('click', () => {
                        const idx = parseInt(d.getAttribute('data-dot'), 10);
                        show(idx);
                        start();
                    });
                });

                if (btnNext) btnNext.addEventListener('click', () => { next(); start(); });
                if (btnPrev) btnPrev.addEventListener('click', () => { prev(); start(); });

                // pause on hover
                root.addEventListener('mouseenter', stop);
                root.addEventListener('mouseleave', start);
            })();
        </script>
    @endif
</section>
@endif
