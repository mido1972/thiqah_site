@php
    use Illuminate\Support\Str;

    $slider = $heroSlider ?? null;
    $locale = $locale ?? request()->query('lang', 'ar');
    $locale = strtolower((string) $locale) === 'en' ? 'en' : 'ar';
    $slides = $slider?->slides?->where('is_active', true)->values() ?? collect();
@endphp

<section class="hero-section style-6 mx-30 nhb-br-0 lg-mx-0 mt-30 lg-mt-0">
    <div class="bg image"><img src="{{ asset('assets/images/banner/hm6-bg01.jpg') }}" alt=""></div>
    <div class="hero-scroll smooth">
        <a href="#about-section" id="scrollLink">
            <div class="scroll-me">{{ $locale === 'en' ? 'Scroll Down' : 'انزل للأسفل' }}</div>
            <div class="hero-social_arrow"><img src="{{ asset('assets/images/icons/arrow-down-long.png') }}" alt=""></div>
        </a>
    </div>
    <div class="p-top-right wow slideInDown" data-wow-delay="500ms" data-wow-duration="1000ms"><img src="{{ asset('assets/images/banner/home4-shape01.png') }}" alt=""></div>
    <div class="hero-slider-6 swiper">
        <div class="swiper-wrapper">
            @forelse($slides as $slide)
                @php
                    $title = $locale === 'en' ? ($slide->title_en ?: $slide->title_ar) : ($slide->title_ar ?: $slide->title_en);
                    $subtitle = $locale === 'en' ? ($slide->subtitle_en ?: $slide->subtitle_ar) : ($slide->subtitle_ar ?: $slide->subtitle_en);
                    $content = $locale === 'en' ? ($slide->content_en ?: $slide->content_ar) : ($slide->content_ar ?: $slide->content_en);
                    $ctaLabel = $locale === 'en' ? ($slide->cta_label_en ?: $slide->cta_label_ar ?: 'Learn More') : ($slide->cta_label_ar ?: $slide->cta_label_en ?: 'اعرف المزيد');
                    $image = !empty($slide->main_image) ? asset('storage/' . ltrim($slide->main_image, '/')) : asset('assets/images/banner/hm6-img01.png');
                @endphp
                <div class="swiper-slide">
                    <div class="container">
                        <div class="row align-items-center thiqah-preserve-layout">
                            <div class="col-lg-7">
                                <div class="hero-content md-mb-50 {{ $locale === 'ar' ? 'text-end' : '' }}">
                                    <h1 class="title">{!! nl2br(e($title ?: ($locale === 'en' ? 'Integrated Software For Modern Operations' : 'حلول برمجية متكاملة لإدارة أعمالك'))) !!}</h1>
                                    <div class="text">
                                        <div class="icon spin"><img src="{{ asset('assets/images/shapes/star3.png') }}" alt=""></div>
                                        <p>{{ Str::limit(trim(strip_tags((string) ($subtitle ?: $content))), 180) }}</p>
                                    </div>
                                    <a href="{{ $slide->cta_url ?: route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-color10">
                                        <span class="link-effect"><span class="effect-1">{{ $ctaLabel }}</span><span class="effect-1">{{ $ctaLabel }}</span></span>
                                        <i class="fa-regular fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="hero-right">
                                    <div class="image-box hero-slide-image-box"><img src="{{ $image }}" alt="{{ $title }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="container">
                        <div class="row align-items-center thiqah-preserve-layout">
                            <div class="col-lg-7">
                                <div class="hero-content md-mb-50 {{ $locale === 'ar' ? 'text-end' : '' }}">
                                    <h1 class="title">{{ $locale === 'en' ? 'ERP, HR and Contracting Solutions' : 'حلول ERP والموارد البشرية والمقاولات' }}</h1>
                                    <div class="text"><div class="icon spin"><img src="{{ asset('assets/images/shapes/star3.png') }}" alt=""></div><p>{{ $locale === 'en' ? 'Professional systems that organize operations, improve visibility and support growth.' : 'أنظمة احترافية لتنظيم التشغيل ورفع كفاءة الأداء ودعم التوسع.' }}</p></div>
                                    <a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-color10"><span class="link-effect"><span class="effect-1">{{ $locale === 'en' ? 'Free Consultation' : 'استشارة مجانية' }}</span><span class="effect-1">{{ $locale === 'en' ? 'Free Consultation' : 'استشارة مجانية' }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="hero-right">
                                    <div class="image-box hero-slide-image-box"><img src="{{ asset('assets/images/banner/hm6-img01.png') }}" alt=""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <div class="info-box style-2 bg-theme3 z-1 shape-mockup-wrap">
        <div class="inner-box">
            <div class="bg image"><img src="{{ asset('assets/images/banner/hm5-info-bg.png') }}" alt=""></div>
            <div class="content"><div class="awards"><span class="count-number odometer" data-count="{{ max($slides->count(), 1) * 4 }}">0</span><span class="plus">+</span></div><p>{{ $locale === 'en' ? 'Integrated business solutions' : 'حلول أعمال متكاملة' }}</p></div>
            <div class="image p-bottom-right shape-mockup" data-right="15px"><img src="{{ asset('assets/images/banner/hm6-info-img01.png') }}" alt=""></div>
        </div>
    </div>
</section>
