@props([
    'title' => '',
    'subtitle' => '',
    'ctaText' => null,
    'ctaUrl' => null,
])

@php
    $locale = request()->query('lang', 'ar') === 'en' ? 'en' : 'ar';
@endphp

<section class="hero-section style-6 mx-30 nhb-br-0 lg-mx-0 mt-30 lg-mt-0">
    <div class="bg image"><img src="{{ asset('assets/images/banner/hm6-bg01.jpg') }}" alt=""></div>
    <div class="container">
        <div class="row align-items-center" style="min-height: 420px;">
            <div class="col-lg-8">
                <div class="hero-content">
                    <div class="thiqah-breadcrumb"><a href="{{ route('website.home', ['lang' => $locale]) }}">{{ $locale === 'en' ? 'Home' : 'الرئيسية' }}</a><span> / {{ $title }}</span></div>
                    <h1 class="title">{{ $title }}</h1>
                    @if($subtitle)
                        <div class="text"><div class="icon spin"><img src="{{ asset('assets/images/shapes/star3.png') }}" alt=""></div><p>{{ $subtitle }}</p></div>
                    @endif
                    @if($ctaText && $ctaUrl)
                        <a href="{{ $ctaUrl }}" class="theme-btn bg-color10"><span class="link-effect"><span class="effect-1">{{ $ctaText }}</span><span class="effect-1">{{ $ctaText }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
