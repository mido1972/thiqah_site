@php
    /** @var \App\Models\HeroSlider|null $slider */
    $slider = $heroSlider ?? null;

    $locale = $locale ?? request()->query('lang', 'ar');
    $locale = strtolower((string) $locale) === 'en' ? 'en' : 'ar';

    $slides = $slider?->slides?->where('is_active', true)->values() ?? collect();

    $intervalMs = (int) ($slider->autoplay_interval_ms ?? 7000);
    $intervalMs = max(1000, min($intervalMs, 60000));
@endphp

@if($slider && $slides->count())
@php
    $sliderDomId = 'hero-slider-' . $slider->id;
@endphp

<section id="{{ $sliderDomId }}" class="hero-shell">
    <div class="hero-panel">
        @foreach($slides as $index => $slide)
            @php
                $isFirst = $index === 0;

                $title = $locale === 'en'
                    ? ($slide->title_en ?: $slide->title_ar)
                    : ($slide->title_ar ?: $slide->title_en);

                $subtitle = $locale === 'en'
                    ? ($slide->subtitle_en ?: $slide->subtitle_ar)
                    : ($slide->subtitle_ar ?: $slide->subtitle_en);

                $content = $locale === 'en'
                    ? ($slide->content_en ?: $slide->content_ar)
                    : ($slide->content_ar ?: $slide->content_en);

                $ctaLabel = $locale === 'en'
                    ? ($slide->cta_label_en ?: $slide->cta_label_ar)
                    : ($slide->cta_label_ar ?: $slide->cta_label_en);

                $mainImage = $slide->main_image ? asset('storage/' . ltrim($slide->main_image, '/')) : null;
            @endphp

            <div
                class="hero-slide absolute inset-0 transition-opacity duration-700 ease-out {{ $isFirst ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none' }}"
                data-slide="{{ $index }}"
            >
                @if($mainImage)
                    <div class="absolute inset-0 scale-[1.03] bg-cover bg-center"
                         style="background-image:url('{{ $mainImage }}')">
                    </div>
                @else
                    <div class="absolute inset-0 bg-slate-950"></div>
                @endif

                <div class="hero-scrim absolute inset-0"></div>

                <div class="relative z-10 mx-auto flex min-h-[560px] max-w-7xl items-center px-4 py-20 sm:px-6 md:min-h-[660px] lg:px-8">
                    <div class="{{ $locale === 'ar' ? 'ms-auto text-right' : 'me-auto text-left' }}">
                        <div class="hero-kicker">
                            <span class="h-2 w-2 rounded-full bg-amber-300"></span>
                            {{ $locale === 'en' ? 'THIQA I-Tech Solutions' : 'ثقة لتقنية نظم المعلومات' }}
                        </div>

                        @if($title)
                            <h1 class="hero-title">
                                {{ $title }}
                            </h1>
                        @endif

                        @if($subtitle)
                            <p class="hero-subtitle">
                                {{ $subtitle }}
                            </p>
                        @endif

                        @if($content)
                            <div class="hero-content prose prose-invert max-w-none">
                                {!! $content !!}
                            </div>
                        @endif

                        @if(!empty($slide->cta_url) && !empty($ctaLabel))
                            <a href="{{ $slide->cta_url }}" class="hero-cta">
                                {{ $ctaLabel }}
                                <span>{{ $locale === 'ar' ? '←' : '→' }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                @if(is_array($slide->overlay_images) && count($slide->overlay_images))
                    <div class="absolute bottom-10 {{ $locale === 'ar' ? 'left-10' : 'right-10' }} z-10 hidden space-y-3 lg:block">
                        @foreach($slide->overlay_images as $img)
                            <img
                                src="{{ asset('storage/' . ltrim($img, '/')) }}"
                                class="max-h-32 rounded-lg border border-white/20 bg-white/10 p-2 opacity-95 shadow-2xl backdrop-blur"
                                alt=""
                            >
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        @if($slides->count() > 1)
            <button type="button"
                    class="hero-prev hero-nav {{ $locale === 'ar' ? 'right-4 md:right-8' : 'left-4 md:left-8' }}"
                    aria-label="Previous slide">
                <span class="text-xl">{{ $locale === 'ar' ? '→' : '←' }}</span>
            </button>

            <button type="button"
                    class="hero-next hero-nav {{ $locale === 'ar' ? 'right-[4.5rem] md:right-[5.5rem]' : 'left-[4.5rem] md:left-[5.5rem]' }}"
                    aria-label="Next slide">
                <span class="text-xl">{{ $locale === 'ar' ? '←' : '→' }}</span>
            </button>
        @endif

        @if($slides->count() > 1)
            <div class="hero-dots absolute bottom-8 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2">
                @foreach($slides as $i => $s)
                    <button type="button"
                            class="hero-dot {{ $i === 0 ? 'bg-amber-300' : 'bg-white/40 hover:bg-white/70' }}"
                            data-go="{{ $i }}"
                            aria-label="Go to slide {{ $i + 1 }}">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    @if($slides->count() > 1)
        <script>
            (function () {
                const root = document.getElementById(@json($sliderDomId));
                if (!root) return;

                const slides = Array.from(root.querySelectorAll('.hero-slide'));
                const dots = Array.from(root.querySelectorAll('.hero-dot'));
                const btnPrev = root.querySelector('.hero-prev');
                const btnNext = root.querySelector('.hero-next');

                let index = 0;
                let timer = null;
                const intervalMs = @json($intervalMs);

                function setActive(i) {
                    index = (i + slides.length) % slides.length;

                    slides.forEach((el, idx) => {
                        const active = idx === index;
                        el.classList.toggle('opacity-100', active);
                        el.classList.toggle('pointer-events-auto', active);
                        el.classList.toggle('opacity-0', !active);
                        el.classList.toggle('pointer-events-none', !active);
                    });

                    dots.forEach((d, idx) => {
                        const active = idx === index;
                        d.classList.toggle('bg-amber-300', active);
                        d.classList.toggle('bg-white/40', !active);
                    });
                }

                function next() { setActive(index + 1); }
                function prev() { setActive(index - 1); }

                function start() {
                    stop();
                    timer = setInterval(() => {
                        if (!document.hidden) next();
                    }, intervalMs);
                }

                function stop() {
                    if (timer) clearInterval(timer);
                    timer = null;
                }

                if (btnNext) btnNext.addEventListener('click', () => { next(); start(); });
                if (btnPrev) btnPrev.addEventListener('click', () => { prev(); start(); });

                dots.forEach(dot => {
                    dot.addEventListener('click', () => {
                        setActive(Number(dot.getAttribute('data-go') || 0));
                        start();
                    });
                });

                root.addEventListener('mouseenter', stop);
                root.addEventListener('mouseleave', start);
                root.addEventListener('focusin', stop);
                root.addEventListener('focusout', start);

                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) stop();
                    else start();
                });

                setActive(0);
                start();
            })();
        </script>
    @endif
</section>
@endif
