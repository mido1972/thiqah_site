@php
    use Illuminate\Support\Str;

    $locale = request()->query('lang', 'ar') === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';
    $aboutShort = trim((string) ($isEn ? (data_get($siteSettings ?? [], 'about_short_en') ?: data_get($siteSettings ?? [], 'about_short')) : (data_get($siteSettings ?? [], 'about_short') ?: data_get($siteSettings ?? [], 'about_short_en'))));
@endphp

@extends('website.layout')

@section('title', $isEn ? 'Thiqah I-Tech' : 'ثقة لتقنية نظم المعلومات')

@section('content')
    @include('website.partials.hero-slider', [
        'heroSlider' => $heroSlider ?? null,
        'locale' => $locale,
    ])

    <section class="about-section style-6 space overflow-hidden bg-theme3" id="about-section">
        <div class="container">
            <div class="row gy-50 align-items-center">
                <div class="col-lg-6">
                    <div class="about-thumb-area mr-60 xl-mr-0">
                        <div class="about-slider swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><div class="about-slide_thumb"><img src="{{ asset('assets/images/about/hm6-img01.jpg') }}" alt=""></div></div>
                                <div class="swiper-slide"><div class="about-slide_thumb"><img src="{{ asset('assets/images/about/hm6-img01.jpg') }}" alt=""></div></div>
                            </div>
                            <div class="array-button"><button class="array-prev"><i class="fa-light fa-arrow-left-long"></i></button><button class="array-next active"><i class="fa-light fa-arrow-right-long"></i></button></div>
                        </div>
                        <div class="customar-box">
                            <div class="box-top"><div class="awards"><span class="count-number odometer" data-count="{{ max(($services ?? collect())->count(), 6) }}">0</span><span class="plus">+</span></div><div class="icon"><i class="fa-solid fa-circle-check"></i></div></div>
                            <div class="box-bottom"><h6>{{ $isEn ? 'Active modules and services' : 'أنظمة وخدمات فعالة' }}</h6></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content-wrapper">
                        <div class="title-area two">
                            <div class="sub-title"><span><i class="asterisk"></i></span>{{ $isEn ? 'About Thiqah' : 'عن ثقة' }}</div>
                            <h2 class="sec-title mb-25">{{ $isEn ? 'Empowering your business with integrated operational systems' : 'نرفع كفاءة أعمالك بأنظمة تشغيل متكاملة' }}</h2>
                            <p class="sec-text text-gray">{{ $aboutShort !== '' ? Str::limit(strip_tags($aboutShort), 220) : ($isEn ? 'We build practical ERP, HR and contracting systems designed for real work environments.' : 'نطوّر أنظمة ERP وموارد بشرية ومقاولات عملية تناسب بيئات العمل الحقيقية.') }}</p>
                        </div>
                        <div class="feature-list">
                            <div class="feature-item"><div class="icon"><i class="flaticon-service"></i></div><p>{{ $isEn ? 'ERP, HR and contracting workflows' : 'عمليات ERP والموارد البشرية والمقاولات' }}</p></div>
                            <div class="feature-item"><div class="icon"><i class="flaticon-people"></i></div><p>{{ $isEn ? 'Implementation, training and support' : 'تنفيذ وتدريب ودعم مستمر' }}</p></div>
                        </div>
                        <div class="pt-35 pb-25"><div class="border"><span class="bar"></span></div></div>
                        <ul class="features-list">
                            <li>{{ $isEn ? 'Clear visibility over finance, projects and teams' : 'رؤية أوضح للحسابات والمشروعات والفرق' }}</li>
                            <li>{{ $isEn ? 'Scalable modules built around your real operation' : 'أنظمة قابلة للتوسع حسب احتياج نشاطك' }}</li>
                        </ul>
                        <a href="{{ route('website.about', ['lang' => $locale]) }}" class="theme-btn bg-dark mt-35"><span class="link-effect"><span class="effect-1">{{ $isEn ? 'More about us' : 'المزيد عنا' }}</span><span class="effect-1">{{ $isEn ? 'More about us' : 'المزيد عنا' }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-top-right wow slideInRight"><img src="{{ asset('assets/images/choose/shape01.png') }}" alt=""></div>
        <div class="p-bottom-right wow img-anim-right"><img src="{{ asset('assets/images/about/hm6-about-line.png') }}" alt=""></div>
    </section>

    <section class="feature-section space bg-dark style-5 mx-30 lg-mx-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="feature-title-area d-flex justify-content-between sm-flex-column sm-mb-30">
                        <div class="title-area white three">
                            <div class="sub-title"><span><i class="asterisk"></i></span>{{ $isEn ? 'OUR SOLUTIONS' : 'حلولنا' }}</div>
                            <h2 class="sec-title mb-0">{{ $isEn ? 'Solutions built to keep your operation running smoothly' : 'حلول مصممة لتحافظ على تشغيل أعمالك بكفاءة' }}</h2>
                        </div>
                        <div class="service-btn sm-justify-content-start"><a href="{{ route('website.services', ['lang' => $locale]) }}" class="theme-btn bg-color10"><span class="link-effect"><span class="effect-1">{{ $isEn ? 'View All Services' : 'عرض كل الخدمات' }}</span><span class="effect-1">{{ $isEn ? 'View All Services' : 'عرض كل الخدمات' }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="row gy-30">
                @foreach(($services ?? collect())->take(3) as $service)
                    @php
                        $title = $isEn ? ($service->title_en ?: $service->title_ar ?: $service->code) : ($service->title_ar ?: $service->title_en ?: $service->code);
                        $desc = $isEn ? ($service->description_en ?? '') : ($service->description_ar ?? '');
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-box-four dark {{ $loop->iteration === 2 ? 'current' : '' }}">
                            <div class="inner">
                                <div class="image-box"><div class="thumb"><img src="{{ asset('assets/images/service/hm5-img0' . min($loop->iteration, 3) . '.jpg') }}" alt=""></div><div class="service-icon"><img src="{{ asset('assets/images/icons/hm6-icon0' . min($loop->iteration, 4) . '.png') }}" alt=""></div></div>
                                <div class="content"><h4 class="title"><a href="{{ route('website.service.show', ['code' => $service->code, 'lang' => $locale]) }}">{{ $title }}</a></h4><p class="text">{{ Str::limit(strip_tags((string) $desc), 120) }}</p><a href="{{ route('website.service.show', ['code' => $service->code, 'lang' => $locale]) }}" class="service-btn">{{ $isEn ? 'Learn More' : 'اعرف المزيد' }} <i class="fa fa-arrow-right-long"></i></a></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="wrapper-section br-20 lg-br-0 overflow-hidden mx-30 lg-mx-0">
        <section class="service-section style-6 bg-white space overflow-hidden">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="service-title-area d-flex justify-content-between sm-flex-column sm-mb-30">
                            <div class="title-area dark three"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $isEn ? 'OUR SERVICES' : 'خدماتنا' }}</div><h2 class="sec-title mb-0">{{ $isEn ? 'Modules that support every part of your business' : 'أنظمة تدعم كل جزء في دورة العمل' }}</h2></div>
                            <div class="service-btn sm-justify-content-start"><a href="{{ route('website.services', ['lang' => $locale]) }}" class="theme-btn bg-transparent"><span class="link-effect"><span class="effect-1">{{ $isEn ? 'View All Services' : 'عرض كل الخدمات' }}</span><span class="effect-1">{{ $isEn ? 'View All Services' : 'عرض كل الخدمات' }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div>
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-lg-12"><div class="service-wrapper">
                    @forelse(($services ?? collect())->take(6) as $service)
                        @php
                            $title = $isEn ? ($service->title_en ?: $service->title_ar ?: $service->code) : ($service->title_ar ?: $service->title_en ?: $service->code);
                            $desc = $isEn ? ($service->description_en ?? '') : ($service->description_ar ?? '');
                        @endphp
                        <div class="service-single-item">
                            <div class="item-left"><div class="image"><img src="{{ asset('assets/images/service/hm6-img01.jpg') }}" alt=""></div><div class="item-wrap"><div class="icon"><img src="{{ asset('assets/images/icons/hm6-icon0' . (($loop->iteration % 4) + 1) . '.png') }}" alt=""></div><h3 class="text"><a href="{{ route('website.service.show', ['code' => $service->code, 'lang' => $locale]) }}">{{ $title }}</a></h3></div></div>
                            <div class="item-right"><div class="item-right-inner"><p>{{ Str::limit(strip_tags((string) $desc), 150) }}</p><a href="{{ route('website.service.show', ['code' => $service->code, 'lang' => $locale]) }}" class="icon"><i class="fa-solid fa-angle-right"></i></a></div></div>
                        </div>
                    @empty
                        <div class="service-single-item"><div class="item-left"><div class="item-wrap"><h3 class="text">{{ $isEn ? 'No services added yet' : 'لا توجد خدمات مضافة بعد' }}</h3></div></div></div>
                    @endforelse
                </div></div></div>
            </div>
        </section>
    </div>

    <div class="contact-wrapper space-bottom bg-theme3">
        <div class="contact-option"><i class="fa-regular fa-envelope"></i><span>{{ $isEn ? 'Looking for help?' : 'تبحث عن مساعدة؟' }}</span><a href="{{ route('website.contact', ['lang' => $locale]) }}" class="contact-link">{{ $isEn ? 'Contact us today' : 'تواصل معنا اليوم' }}</a></div>
        <div class="social-option"><i class="fa-regular fa-thumbs-up"></i><span>{{ $isEn ? 'Discover our solutions' : 'تعرّف على حلولنا' }}</span><a href="{{ route('website.services', ['lang' => $locale]) }}" class="social-link">{{ $isEn ? 'Browse all services' : 'استعرض كل الخدمات' }}</a></div>
    </div>
@endsection
