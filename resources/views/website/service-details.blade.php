@php
    use Illuminate\Support\Str;

    $locale = request()->query('lang', 'ar') === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';
    $title = $isEn ? ($service->title_en ?: $service->title_ar ?: $service->code) : ($service->title_ar ?: $service->title_en ?: $service->code);
    $description = $isEn ? ($service->description_en ?? '') : ($service->description_ar ?? '');
@endphp

@extends('website.layout')

@section('title', $title)
@section('meta_description', Str::limit(strip_tags((string) $description), 160))

@section('content')
    @include('website.partials.page-hero', [
        'title' => $title,
        'subtitle' => Str::limit(strip_tags((string) $description), 180),
        'ctaText' => $isEn ? 'Request a quote' : 'اطلب عرض سعر',
        'ctaUrl' => route('website.contact', ['lang' => $locale]),
    ])

    <section class="thiqah-section bg-white">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="thiqah-card">
                        <div class="title-area two mb-25">
                            <div class="sub-title"><span><i class="asterisk"></i></span>{{ $isEn ? 'Service Details' : 'تفاصيل الخدمة' }}</div>
                            <h2 class="sec-title">{{ $title }}</h2>
                        </div>
                        <div class="thiqah-richtext">
                            {!! $description !== '' ? $description : ($isEn ? '<p>Service details will be added soon.</p>' : '<p>سيتم إضافة تفاصيل الخدمة قريبًا.</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="thiqah-card">
                        <h4 class="mb-20">{{ $isEn ? 'Need implementation support?' : 'تحتاج إلى دعم في التنفيذ؟' }}</h4>
                        <p>{{ $isEn ? 'Our team can help you scope the right modules, timeline and rollout plan.' : 'فريقنا يساعدك في تحديد الأنظمة المناسبة وخطة التنفيذ والجدول الزمني.' }}</p>
                        <a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-theme mt-30"><span class="link-effect"><span class="effect-1">{{ $isEn ? 'Talk to us' : 'تحدث معنا' }}</span><span class="effect-1">{{ $isEn ? 'Talk to us' : 'تحدث معنا' }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section style-4 bg-theme2 mx-30 lg-mx-0">
        <div class="bg image mbm-color-dodge"><img src="{{ asset('assets/images/cta/hm6-bg01.png') }}" alt=""></div>
        <div class="overlay"></div>
        <div class="container py-75">
            <div class="row gy-30 align-items-center">
                <div class="col-lg-7"><div class="social-proof"><div class="icon"><i class="fa-solid fa-check"></i></div><p class="text">{{ $isEn ? 'Want this module running in your business?' : 'تريد تشغيل هذا النظام في شركتك؟' }}</p></div></div>
                <div class="col-lg-5"><div class="cta-btn text-{{ $isEn ? 'right' : 'left' }} md-text-{{ $isEn ? 'left' : 'right' }}"><a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-dark"><span class="link-effect"><span class="effect-1">{{ $isEn ? 'Request a quote' : 'اطلب عرض سعر' }}</span><span class="effect-1">{{ $isEn ? 'Request a quote' : 'اطلب عرض سعر' }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div></div>
            </div>
        </div>
    </section>
@endsection
