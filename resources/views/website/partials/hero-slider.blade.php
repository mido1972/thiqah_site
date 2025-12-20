@php
    /** @var \App\Models\HeroSlider|null $slider */
    $slider = $heroSlider ?? null;

    $locale = $locale ?? request()->query('lang', 'ar');
    $locale = strtolower((string) $locale) === 'en' ? 'en' : 'ar';

    $slides = $slider?->slides?->where('is_active', true)->values() ?? collect();

    // ✅ Auto-play interval (ms) from DB (with safety clamp)
    $intervalMs = (int) ($slider->autoplay_interval_ms ?? 7000);
    $intervalMs = max(1000, min($intervalMs, 60000)); // 1s .. 60s
@endphp

@if($slider && $slides->count())
@php
    $sliderDomId = 'hero-slider-' . $slider->id;
@endphp

<section id="{{ $sliderDomId }}" class="relative w-full overflow-hidden">
    <div class="relative min-h-[520px] md:min-h-[600px]">
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
                {{-- Background image --}}
                @if($mainImage)
                    <div class="absolute inset-0 bg-cover bg-center"
                         style="background-image:url('{{ $mainImage }}')">
                    </div>
                @endif

                {{-- Overlay (ممكن نزوده لو الصور عليها نصوص) --}}
                <div class="absolute inset-0 bg-slate-950/60"></div>

                {{-- Content --}}
                <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 md:py-28 text-white">
                    <div class="max-w-2xl {{ $locale === 'ar' ? 'ms-auto text-right' : 'me-auto text-left' }}">
                        @if($title)
                            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4 drop-shadow">
                                {{ $title }}
                            </h1>
                        @endif

                        @if($subtitle)
                            <p class="text-lg md:text-xl opacity-95 mb-3">
                                {{ $subtitle }}
                            </p>
                        @endif

                        @if($content)
                            <div class="prose prose-invert max-w-none mb-6 text-white/95">
                                {!! $content !!}
                            </div>
                        @endif

                        @if(!empty($slide->cta_url) && !empty($ctaLabel))
                            <a href="{{ $slide->cta_url }}"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl
                                      bg-orange-500 hover:bg-orange-600 text-white font-bold transition">
                                {{ $ctaLabel }}
                                <span class="text-white/90">{{ $locale === 'ar' ? '←' : '→' }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Overlay Images (اختياري) --}}
                @if(is_array($slide->overlay_images) && count($slide->overlay_images))
                    <div class="hidden lg:block absolute bottom-10 {{ $locale === 'ar' ? 'left-10' : 'right-10' }} z-10 space-y-3">
                        @foreach($slide->overlay_images as $img)
                            <img
                                src="{{ asset('storage/' . ltrim($img, '/')) }}"
                                class="max-h-32 opacity-90 rounded-lg shadow"
                                alt=""
                            >
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        {{-- Arrows --}}
        @if($slides->count() > 1)
            <button type="button"
                    class="hero-prev absolute top-1/2 -translate-y-1/2 z-20 {{ $locale === 'ar' ? 'right-6' : 'left-6' }}
                           h-11 w-11 rounded-full border border-white/60 bg-black/25 hover:bg-black/40 transition
                           flex items-center justify-center text-white"
                    aria-label="Previous slide">
                <span class="text-xl">{{ $locale === 'ar' ? '→' : '←' }}</span>
            </button>

            <button type="button"
                    class="hero-next absolute top-1/2 -translate-y-1/2 z-20 {{ $locale === 'ar' ? 'right-20' : 'left-20' }}
                           h-11 w-11 rounded-full border border-white/60 bg-black/25 hover:bg-black/40 transition
                           flex items-center justify-center text-white"
                    aria-label="Next slide">
                <span class="text-xl">{{ $locale === 'ar' ? '←' : '→' }}</span>
            </button>
        @endif

        {{-- Dots --}}
        @if($slides->count() > 1)
            <div class="hero-dots absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
                @foreach($slides as $i => $s)
                    <button type="button"
                            class="hero-dot h-2.5 w-2.5 rounded-full transition
                                   {{ $i === 0 ? 'bg-white' : 'bg-white/40 hover:bg-white/70' }}"
                            data-go="{{ $i }}"
                            aria-label="Go to slide {{ $i + 1 }}">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ✅ Auto-play script (Vanilla, scoped لكل سلايدر) --}}
    @if($slides->count() > 1)
        <script>
            (function () {
                const root = document.getElementById(@json($sliderDomId));
                if (!root) return;

                const slides = Array.from(root.querySelectorAll('.hero-slide'));
                const dots   = Array.from(root.querySelectorAll('.hero-dot'));
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
                        d.classList.toggle('bg-white', active);
                        d.classList.toggle('bg-white/40', !active);
                    });
                }

                function next() { setActive(index + 1); }
                function prev() { setActive(index - 1); }

                function start() {
                    stop();
                    timer = setInterval(() => {
                        // لو المستخدم على تبويب تاني، ما نبدّلاش
                        if (document.hidden) return;
                        next();
                    }, intervalMs);
                }

                function stop() {
                    if (timer) clearInterval(timer);
                    timer = null;
                }

                // Buttons
                if (btnNext) btnNext.addEventListener('click', () => { next(); start(); });
                if (btnPrev) btnPrev.addEventListener('click', () => { prev(); start(); });

                // Dots
                dots.forEach(dot => {
                    dot.addEventListener('click', () => {
                        const go = Number(dot.getAttribute('data-go') || 0);
                        setActive(go);
                        start();
                    });
                });

                // Pause on hover / focus
                root.addEventListener('mouseenter', stop);
                root.addEventListener('mouseleave', start);
                root.addEventListener('focusin', stop);
                root.addEventListener('focusout', start);

                // Stop when tab hidden, resume when visible
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) stop();
                    else start();
                });

                // Init
                setActive(0);
                start();
            })();
        </script>
    @endif
</section>
@endif
