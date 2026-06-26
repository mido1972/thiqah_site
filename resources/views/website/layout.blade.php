@php
    if (!isset($siteSettings) || empty($siteSettings)) {
        if (isset($settings) && $settings) {
            $siteSettings = is_array($settings) ? $settings : $settings->toArray();
        } else {
            $siteSettings = [];
        }
    }

    $appLocale = $appLocale ?? request()->query('lang', 'ar');
    $appLocale = strtolower((string) $appLocale) === 'en' ? 'en' : 'ar';
    $isRtl = $appLocale === 'ar';
    app()->setLocale($appLocale);

    $ss = fn ($key, $default = null) => data_get($siteSettings, $key, $default);
    $defaultTitle = $appLocale === 'en'
        ? ($ss('meta_title_en', $ss('company_name_en', 'Thiqah I-Tech')))
        : ($ss('meta_title_ar', $ss('company_name', 'ثقة لتقنية نظم المعلومات')));
    $defaultDesc = $appLocale === 'en'
        ? (string) $ss('meta_description_en', '')
        : (string) $ss('meta_description_ar', '');

    $pageTitle = trim((string) $__env->yieldContent('title')) ?: $defaultTitle;
    $pageDesc = trim((string) $__env->yieldContent('meta_description')) ?: $defaultDesc;

    $faviconRaw = (string) ($ss('favicon') ?? '');
    $faviconUrl = asset('assets/images/favicons/favicon.png');
    if ($faviconRaw !== '') {
        $faviconUrl = str_starts_with($faviconRaw, 'http')
            ? $faviconRaw
            : asset('storage/' . ltrim(preg_replace('#^storage/#', '', $faviconRaw), '/'));
    }

    $shouldUseViteDevServer = false;
    $hotFile = public_path('hot');
    $manifestFile = public_path('build/manifest.json');
    $manifest = [];

    if (is_file($hotFile)) {
        $hotUrl = trim((string) file_get_contents($hotFile));
        $host = parse_url($hotUrl, PHP_URL_HOST);
        $port = (int) (parse_url($hotUrl, PHP_URL_PORT) ?: 5173);
        if ($host) {
            $connection = @fsockopen($host, $port, $errno, $errstr, 0.3);
            if (is_resource($connection)) {
                fclose($connection);
                $shouldUseViteDevServer = true;
            }
        }
    }

    if (!$shouldUseViteDevServer && is_file($manifestFile)) {
        $manifest = json_decode((string) file_get_contents($manifestFile), true) ?: [];
    }
@endphp
<!DOCTYPE html>
<html class="no-js" lang="{{ $appLocale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $pageTitle }}</title>
    @if($pageDesc !== '')
        <meta name="description" content="{{ $pageDesc }}">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{ $faviconUrl }}" sizes="32x32" type="image/png">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @if($shouldUseViteDevServer)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @if(!empty($manifest['resources/css/app.css']['file']))
            <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
        @endif
        @if(!empty($manifest['resources/js/app.js']['file']))
            <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
        @endif
    @endif

    <style>
        body,
        body * {
            font-family: {{ $isRtl ? "'Cairo', sans-serif" : "'Inter', sans-serif" }};
        }
        body {
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }
        .rtl-fix .title-area,
        .rtl-fix .hero-content,
        .rtl-fix .about-content-wrapper,
        .rtl-fix .thiqah-breadcrumb,
        .rtl-fix .thiqah-card,
        .rtl-fix .footer-widget,
        .rtl-fix .newsletter,
        .rtl-fix .service-single-item .item-right-inner,
        .rtl-fix .contact-option,
        .rtl-fix .social-option {
            text-align: right;
        }
        .rtl-fix .header-right,
        .rtl-fix .footer-policy,
        .rtl-fix .contact-option,
        .rtl-fix .social-option {
            direction: rtl;
        }
        .thiqah-preserve-layout,
        .thiqah-preserve-layout.row,
        .thiqah-site-header .menu-area > .row,
        .hero-slider-6 .swiper-slide .thiqah-preserve-layout {
            direction: ltr;
        }
        .rtl-fix .thiqah-site-header .main-menu,
        .rtl-fix .thiqah-site-header .main-menu a,
        .rtl-fix .thiqah-site-header .header-logo,
        .rtl-fix .hero-slider-6 .hero-content,
        .rtl-fix .hero-slider-6 .hero-scroll,
        .rtl-fix .footer-section .footer-widget,
        .rtl-fix .footer-section .brand-header,
        .rtl-fix .footer-section .newsletter {
            direction: rtl;
        }
        .thiqah-site-header .main-menu ul.sub-menu {
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }
        .rtl-fix .thiqah-site-header .main-menu > ul {
            flex-direction: row;
        }
        .rtl-fix .thiqah-site-header .main-menu > ul > li.menu-item-has-children > a {
            padding-right: 18px;
            padding-left: 0;
        }
        .rtl-fix .thiqah-site-header .main-menu > ul > li.menu-item-has-children > a::before {
            right: 0;
            left: auto;
        }
        .rtl-fix .thiqah-site-header .main-menu ul.sub-menu {
            left: 0;
            right: auto;
        }
        .rtl-fix .thiqah-site-header .main-menu ul.sub-menu li ul.sub-menu {
            left: 100%;
            right: auto;
            margin-left: 15px;
            margin-right: 0;
        }
        .thiqah-site-header .lang-switcher,
        .thiqah-site-header .mobile-lang-switcher {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .thiqah-site-header {
            top: 0;
        }
        .thiqah-site-header .header-right-wrapper .lang-switcher {
            display: none;
        }
        .thiqah-site-header .header-right {
            direction: ltr;
        }
        .thiqah-site-header .header-logo a {
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            width: 140px;
            height: 40px;
            overflow: hidden;
        }
        .thiqah-site-header .header-logo a img {
            display: block;
            max-width: 140px;
            max-height: 40px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .thiqah-site-header .lang-switcher {
            direction: ltr;
        }
        .thiqah-site-header .lang-switcher a,
        .thiqah-site-header .mobile-lang-switcher a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .04em;
            color: rgba(255,255,255,.85);
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.1);
        }
        .thiqah-site-header .lang-switcher a.active,
        .thiqah-site-header .mobile-lang-switcher a.active {
            color: #fff;
            background: #e3572b;
            border-color: #e3572b;
        }
        .thiqah-site-header .mobile-lang-switcher {
            margin: 24px 0;
            justify-content: {{ $isRtl ? 'flex-end' : 'flex-start' }};
        }
        .thiqah-site-header .main-wrapper .nav-menu {
            margin-left: -25px;
        }
        .thiqah-site-header .main-menu > ul > li > a {
            font-size: 16px;
            font-weight: 500;
        }
        .thiqah-site-header .header-right .theme-btn {
            min-width: 200px;
            padding: 13px 40px;
            justify-content: center;
            white-space: nowrap;
            font-size: 16px;
        }
        .thiqah-section {
            padding: 120px 0;
        }
        .thiqah-card {
            background: #fff;
            border: 1px solid rgba(19, 28, 51, 0.08);
            border-radius: 24px;
            padding: 36px;
            box-shadow: 0 20px 60px rgba(16, 24, 40, 0.08);
        }
        .thiqah-form-control,
        .thiqah-form-textarea {
            width: 100%;
            border: 1px solid #d9dde7;
            border-radius: 16px;
            background: #fff;
            color: #101828;
            padding: 15px 18px;
        }
        .thiqah-form-control:focus,
        .thiqah-form-textarea:focus {
            outline: none;
            border-color: #ff9800;
            box-shadow: 0 0 0 4px rgba(255, 152, 0, 0.12);
        }
        .thiqah-form-textarea {
            min-height: 180px;
            resize: vertical;
        }
        .thiqah-breadcrumb {
            color: rgba(255,255,255,.75);
            margin-bottom: 14px;
        }
        .thiqah-breadcrumb a {
            color: #fff;
        }
        .thiqah-breadcrumb span {
            color: #ff9800;
        }
        .thiqah-richtext,
        .thiqah-richtext p,
        .thiqah-richtext li {
            color: #5c6574;
            line-height: 1.9;
        }
        .thiqah-richtext h2,
        .thiqah-richtext h3,
        .thiqah-richtext h4 {
            color: #101828;
            margin-bottom: 16px;
        }
        .thiqah-richtext ul,
        .thiqah-richtext ol {
            padding-{{ $isRtl ? 'right' : 'left' }}: 24px;
        }
        .footer-section .newsletter,
        .footer-section .footer-widget,
        .footer-section .brand-header,
        .footer-section .footer-bottom,
        .footer-section .footer-policy {
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }
        .footer-section .newsletter {
            direction: {{ $isRtl ? 'rtl' : 'ltr' }};
        }
        .footer-section .footer-top {
            font-size: 16px;
        }
        .footer-section .footer-widget .title {
            font-size: 30px;
            line-height: 1.3;
            font-weight: 700;
            margin-bottom: 22px;
        }
        .footer-section .brand-header .text,
        .footer-section .footer-widget .text,
        .footer-section .list-unstyled li a,
        .footer-section .notify,
        .footer-section .footer-bottom p,
        .footer-section .footer-policy a {
            font-size: 18px;
            line-height: 1.9;
        }
        .footer-section .list-unstyled li {
            margin-bottom: 8px;
        }
        .footer-section .contact-info .email-details a {
            font-size: 22px;
            font-weight: 700;
        }
        .footer-section .contact-info .email-details p,
        .footer-section .newsletter .text h3 {
            font-size: 20px;
            line-height: 1.6;
        }
        .footer-section .footer-social .social-link {
            font-size: 15px;
            font-weight: 700;
        }
        .footer-section .notify {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .hero-slider-6 .swiper-slide .row {
            min-height: 680px;
        }
        .hero-slider-6 .hero-right {
            min-height: 680px;
            padding-left: 50px;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
        }
        .hero-slider-6 .hero-content {
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }
        .hero-slider-6 .hero-content .text {
            justify-content: {{ $isRtl ? 'flex-end' : 'flex-start' }};
        }
        .hero-slider-6 .hero-slide-image-box,
        .hero-slider-6 .hero-right .image-box {
            width: 645px;
            max-width: 100%;
            height: 720px;
            margin-bottom: -124px;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            overflow: hidden;
        }
        .hero-slider-6 .hero-slide-image-box img,
        .hero-slider-6 .hero-right .image-box img {
            width: 100% !important;
            height: 100% !important;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            object-position: bottom center;
            display: block;
        }
        .hero-slider-6 .hero-content .title,
        .hero-slider-6 .hero-content p,
        .hero-slider-6 .scroll-me {
            font-family: {{ $isRtl ? "'Cairo', sans-serif" : "'Inter', sans-serif" }};
        }
        @media (max-width: 991px) {
            .thiqah-section {
                padding: 90px 0;
            }
            .thiqah-site-header {
                top: 0;
            }
            .thiqah-site-header .header-logo a {
                width: 120px;
                height: 34px;
            }
            .thiqah-site-header .header-logo a img {
                max-width: 120px;
                max-height: 34px;
            }
            .hero-slider-6 .swiper-slide .row {
                min-height: 460px;
            }
            .hero-slider-6 .hero-right {
                display: none;
            }
            .footer-section .footer-widget .title {
                font-size: 24px;
            }
            .footer-section .brand-header .text,
            .footer-section .footer-widget .text,
            .footer-section .list-unstyled li a,
            .footer-section .notify,
            .footer-section .footer-bottom p,
            .footer-section .footer-policy a {
                font-size: 16px;
            }
        }
        @media (max-width: 767px) {
            .thiqah-card {
                padding: 24px;
                border-radius: 20px;
            }
            .thiqah-site-header .main-wrapper .nav-menu {
                margin-left: 0;
            }
            .hero-slider-6 .swiper-slide .row {
                min-height: 320px;
            }
            .footer-section .contact-info .email-details a {
                font-size: 18px;
            }
            .footer-section .contact-info .email-details p,
            .footer-section .newsletter .text h3 {
                font-size: 17px;
            }
        }
    </style>

    @yield('extra_head')
</head>
<body id="body" class="bg-theme3 {{ $isRtl ? 'rtl-fix' : '' }}">
    <div class="page-wrapper bg-theme3 overflow-visible">
        <div class="loading-screen" id="loading-screen">
            <div class="preloader-close">x</div>
            <div class="animation-preloader">
                <div class="txt-loading">
                    <span data-text-preloader="T" class="letters-loading">T</span>
                    <span data-text-preloader="H" class="letters-loading">H</span>
                    <span data-text-preloader="I" class="letters-loading">I</span>
                    <span data-text-preloader="Q" class="letters-loading">Q</span>
                    <span data-text-preloader="A" class="letters-loading">A</span>
                </div>
            </div>
        </div>

        @include('website.partials.header')
        <main>@yield('content')</main>
        @include('website.partials.footer')
    </div>

    <div class="scrollToTop active-progress">
        <div class="arrowUp"><i class="fa-light fa-arrow-up"></i></div>
        <div class="water">
            <svg viewBox="0 0 560 20" class="water_wave water_wave_back"><use xlink:href="#wave"></use></svg>
            <svg viewBox="0 0 560 20" class="water_wave water_wave_front"><use xlink:href="#wave"></use></svg>
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 560 20" style="display:none;">
                <symbol id="wave">
                    <path d="M420,20c21.5-0.4,38.8-2.5,51.1-4.5c13.4-2.2,26.5-5.2,27.3-5.4C514,6.5,518,4.7,528.5,2.7c7.1-1.3,17.9-2.8,31.5-2.7v20H420z" fill="#ff9800"></path>
                    <path d="M420,20c-21.5-0.4-38.8-2.5-51.1-4.5c-13.4-2.2-26.5-5.2-27.3-5.4C326,6.5,322,4.7,311.5,2.7C304.3,1.4,293.6-0.1,280,0v20H420z" fill="#ffb300"></path>
                    <path d="M140,20c21.5-0.4,38.8-2.5,51.1-4.5c13.4-2.2,26.5-5.2,27.3-5.4C234,6.5,238,4.7,248.5,2.7c7.1-1.3,17.9-2.8,31.5-2.7v20H140z" fill="#ff9800"></path>
                    <path d="M140,20c-21.5-0.4-38.8-2.5-51.1-4.5c-13.4-2.2-26.5-5.2-27.3-5.4C46,6.5,42,4.7,31.5,2.7C24.3,1.4,13.6-0.1,0,0v20H140z" fill="#ffb300"></path>
                </symbol>
            </svg>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/marquee.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.odometer.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/lenis.min.js') }}"></script>
    <script src="{{ asset('assets/js/splite-type.min.js') }}"></script>
    <script src="{{ asset('assets/js/vanilla-tilt.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
