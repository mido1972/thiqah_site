@extends('website.layout')

@php
    use Illuminate\Support\Str;

    $locale = request()->query('lang', 'ar') === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';
    $t = fn ($ar, $en) => $isEn ? $en : $ar;
    $services = $services ?? collect();

    $serviceTitle = fn ($s) => $isEn ? ($s->title_en ?: $s->title_ar ?: $s->code) : ($s->title_ar ?: $s->title_en ?: $s->code);
    $serviceDesc  = fn ($s) => $isEn ? (string) ($s->description_en ?? '') : (string) ($s->description_ar ?? '');
    $serviceUrl   = fn ($s) => route('website.service.show', ['code' => $s->code, 'lang' => $locale]);
@endphp

@section('title', $t('الأنظمة والخدمات', 'Modules & Services'))
@section('meta_description', $t('باقة متكاملة تغطي الحسابات والمخازن والمبيعات والفاتورة الإلكترونية والموارد البشرية والمقاولات والمطاعم.', 'A full suite covering accounting, inventory, sales, e-invoicing, HR, contracting and restaurants.'))

@section('content')

    @include('website.partials.page-hero', [
        'title' => $t('الأنظمة والخدمات', 'Modules & Services'),
        'subtitle' => $t('اختر الأنظمة المناسبة لنشاطك، ثم اطلب عرضًا وخطة تنفيذ.', 'Pick the modules that fit your business, then request a proposal and rollout plan.'),
    ])

    <div class="wrapper-section br-20 lg-br-0 overflow-hidden mx-30 lg-mx-0">
        <section class="service-section style-6 bg-white space overflow-hidden">
            <div class="container">
                <div class="row"><div class="col-lg-12">
                    <div class="service-title-area d-flex justify-content-between sm-flex-column sm-mb-30">
                        <div class="title-area dark three"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('أنظمتنا', 'Our modules') }}</div><h2 class="sec-title mb-0">{{ $t('الأنظمة المتاحة للتشغيل', 'Modules ready to deploy') }}</h2></div>
                        <div class="service-btn sm-justify-content-start"><a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-transparent"><span class="link-effect"><span class="effect-1">{{ $t('اطلب عرضًا', 'Request a proposal') }}</span><span class="effect-1">{{ $t('اطلب عرضًا', 'Request a proposal') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div>
                    </div>
                </div></div>
                <div class="row"><div class="col-lg-12"><div class="service-wrapper">
                    @forelse($services as $service)
                        <div class="service-single-item">
                            <div class="item-left">
                                <div class="image"><img src="{{ asset('assets/images/service/hm6-img01.jpg') }}" alt=""></div>
                                <div class="item-wrap">
                                    <div class="icon">@if(!empty($service->icon_class))<i class="{{ $service->icon_class }}"></i>@else<img src="{{ asset('assets/images/icons/hm6-icon0' . (($loop->iteration % 4) + 1) . '.png') }}" alt="">@endif</div>
                                    <h3 class="text"><a href="{{ $serviceUrl($service) }}">{{ $serviceTitle($service) }}</a></h3>
                                </div>
                            </div>
                            <div class="item-right"><div class="item-right-inner"><p>{{ Str::limit(strip_tags($serviceDesc($service)), 160) }}</p><a href="{{ $serviceUrl($service) }}" class="icon"><i class="fa-solid fa-angle-{{ $isEn ? 'right' : 'left' }}"></i></a></div></div>
                        </div>
                    @empty
                        <div class="service-single-item"><div class="item-left"><div class="item-wrap"><h3 class="text">{{ $t('لا توجد أنظمة مضافة بعد. أضفها من لوحة التحكم.', 'No modules yet. Add them from the admin panel.') }}</h3></div></div></div>
                    @endforelse
                </div></div></div>
            </div>
        </section>
    </div>

    {{-- CTA --}}
    <section class="cta-section style-4 bg-theme2 mx-30 lg-mx-0">
        <div class="bg image mbm-color-dodge"><img src="{{ asset('assets/images/cta/hm6-bg01.png') }}" alt=""></div>
        <div class="overlay"></div>
        <div class="container py-75">
            <div class="row gy-30 align-items-center">
                <div class="col-lg-7"><div class="social-proof"><div class="icon"><i class="fa-solid fa-check"></i></div><p class="text">{{ $t('غير متأكد أي نظام يناسبك؟ نساعدك في الاختيار.', 'Not sure which module fits? We will help you choose.') }}</p></div></div>
                <div class="col-lg-5"><div class="cta-btn text-{{ $isEn ? 'right' : 'left' }} md-text-{{ $isEn ? 'left' : 'right' }}"><a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-dark"><span class="link-effect"><span class="effect-1">{{ $t('احجز استشارة', 'Book a consultation') }}</span><span class="effect-1">{{ $t('احجز استشارة', 'Book a consultation') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div></div>
            </div>
        </div>
    </section>

@endsection
