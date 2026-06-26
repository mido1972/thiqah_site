@php
    $appLocale = $appLocale ?? request()->query('lang', 'ar');
    $appLocale = strtolower((string) $appLocale) === 'en' ? 'en' : 'ar';

    $itemTitle = fn ($item) => $appLocale === 'en'
        ? ($item->title_en ?: $item->title_ar)
        : ($item->title_ar ?: $item->title_en);

    $itemUrl = function ($item) use ($appLocale) {
        if (!empty($item->route_name) && \Route::has($item->route_name)) {
            try { return route($item->route_name, ['lang' => $appLocale]); } catch (\Throwable $e) {}
        }
        if (($item->type ?? '') === 'page' && !empty($item->page_id) && !empty($item->page?->slug)) {
            return route('website.page', ['slug' => $item->page->slug, 'lang' => $appLocale]);
        }
        $url = trim((string) ($item->url ?? ''));
        if ($url === '') return '#';
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) return $url;
        if (str_starts_with($url, '#') || str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:')) return $url;
        return url('/' . ltrim($url, '/')) . '?lang=' . $appLocale;
    };

    $itemTarget = fn ($item) => !empty($item->open_in_new_tab) ? ' target="_blank" rel="noopener noreferrer"' : '';
    $companyName = $appLocale === 'en' ? ($companyNameEn ?? data_get($siteSettings ?? [], 'company_name_en', 'Thiqah')) : ($companyNameAr ?? data_get($siteSettings ?? [], 'company_name', 'ثقة'));
    $companyLogo = $companyLogo ?? data_get($siteSettings ?? [], 'company_logo') ?? data_get($siteSettings ?? [], 'logo');
    $logoUrl = $companyLogo ? (str_starts_with($companyLogo, 'http') ? $companyLogo : asset('storage/' . ltrim(preg_replace('#^storage/#', '', $companyLogo), '/'))) : asset('assets/images/logo/logo-2.png');
    $logoStickyUrl = $companyLogo ? $logoUrl : asset('assets/images/logo/logo.png');
    $ctaLabel = $ctaLabel ?? ($appLocale === 'en' ? 'Get a Quote' : 'اطلب عرض سعر');
    $ctaUrl = $ctaUrl ?? route('website.contact', ['lang' => $appLocale]);
    $address = $appLocale === 'en' ? (data_get($siteSettings ?? [], 'address_en') ?: data_get($siteSettings ?? [], 'address') ?: 'Cairo, Egypt') : (data_get($siteSettings ?? [], 'address') ?: data_get($siteSettings ?? [], 'address_en') ?: 'القاهرة، مصر');
    $aboutShort = $appLocale === 'en' ? (data_get($siteSettings ?? [], 'about_short_en') ?: 'Integrated ERP, HR and contracting systems for growing businesses.') : (data_get($siteSettings ?? [], 'about_short') ?: 'حلول متكاملة لإدارة ERP والموارد البشرية والمقاولات للشركات الطموحة.');
    $topPhone = $topPhone ?? $companyPhone ?? null;
    $topEmail = $topEmail ?? $companyEmail ?? null;
    $headerMenuTree = $headerMenuTree ?? collect();
    $social = collect($socialLinks ?? [])->filter();
    $socialIcons = [
        'facebook' => 'fab fa-facebook-f',
        'instagram' => 'fab fa-instagram',
        'linkedin' => 'fab fa-linkedin-in',
        'twitter' => 'fab fa-twitter',
        'youtube' => 'fab fa-youtube',
        'tiktok' => 'fab fa-tiktok',
        'whatsapp' => 'fab fa-whatsapp',
    ];
@endphp

<header class="nav-header header-style7 thiqah-site-header">
    <div class="sticky-wrapper">
        <div class="main-wrapper">
            <div class="menu-area">
                <div class="row align-items-center justify-content-between thiqah-preserve-layout">
                    <div class="col-auto logo">
                        <div class="header-logo">
                            <a href="{{ route('website.home', ['lang' => $appLocale]) }}">
                                <img alt="{{ $companyName }}" src="{{ $logoUrl }}">
                                <img alt="{{ $companyName }}" src="{{ $logoStickyUrl }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-auto nav-menu">
                        <nav class="main-menu d-none d-lg-inline-block lh-1">
                            <ul class="navigation">
                                @foreach($headerMenuTree as $item)
                                    @php $hasChildren = isset($item->children) && $item->children->count() > 0; @endphp
                                    <li class="{{ $hasChildren ? 'menu-item-has-children' : '' }}">
                                        <a href="{{ $hasChildren ? '#' : $itemUrl($item) }}"{!! $hasChildren ? '' : $itemTarget($item) !!}>{{ $itemTitle($item) }}</a>
                                        @if($hasChildren)
                                            <ul class="sub-menu">
                                                @foreach($item->children as $child)
                                                    <li><a href="{{ $itemUrl($child) }}"{!! $itemTarget($child) !!}>{{ $itemTitle($child) }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                        <div class="navbar-right d-inline-flex d-lg-none">
                            <button class="menu-toggle sidebar-btn" type="button">
                                <span class="line"></span><span class="line"></span><span class="line"></span>
                            </button>
                        </div>
                    </div>
                    <div class="col-auto header-right-wrapper">
                        <div class="header-right">
                            <div class="lang-switcher" aria-label="{{ $appLocale === 'en' ? 'Language switcher' : 'تبديل اللغة' }}">
                                <a href="{{ $switchToEnUrl }}" class="{{ $appLocale === 'en' ? 'active' : '' }}">EN</a>
                                <a href="{{ $switchToArUrl }}" class="{{ $appLocale === 'ar' ? 'active' : '' }}">AR</a>
                            </div>
                            <button class="search-btn"><span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span></button>
                            <a href="{{ $ctaUrl }}" class="theme-btn bg-theme">
                                <span class="link-effect"><span class="effect-1">{{ $ctaLabel }}</span><span class="effect-1">{{ $ctaLabel }}</span></span>
                                <i class="fa-regular fa-arrow-right-long"></i>
                            </a>
                            <div class="sidebar-icon"><button class="sidebar-tab open"><img src="{{ asset('assets/images/icons/hm6-dot_icon.png') }}" alt=""></button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="mobile-menu-wrapper">
    <div class="mobile-menu-area">
        <button class="menu-toggle"><i class="fas fa-times"></i></button>
        <div class="mobile-logo"><a href="{{ route('website.home', ['lang' => $appLocale]) }}"><img alt="{{ $companyName }}" src="{{ $logoUrl }}"></a></div>
        <div class="mobile-menu">
            <ul class="navigation clearfix">
                @foreach($headerMenuTree as $item)
                    @php $hasChildren = isset($item->children) && $item->children->count() > 0; @endphp
                    <li class="{{ $hasChildren ? 'menu-item-has-children' : '' }}">
                        <a href="{{ $hasChildren ? '#' : $itemUrl($item) }}"{!! $hasChildren ? '' : $itemTarget($item) !!}>{{ $itemTitle($item) }}</a>
                        @if($hasChildren)
                            <ul class="sub-menu">
                                @foreach($item->children as $child)
                                    <li><a href="{{ $itemUrl($child) }}"{!! $itemTarget($child) !!}>{{ $itemTitle($child) }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mobile-lang-switcher">
            <a href="{{ $switchToEnUrl }}" class="{{ $appLocale === 'en' ? 'active' : '' }}">English</a>
            <a href="{{ $switchToArUrl }}" class="{{ $appLocale === 'ar' ? 'active' : '' }}">العربية</a>
        </div>
        <div class="sidebar-wrap"><h6>{{ $address }}</h6></div>
        <div class="sidebar-wrap">
            @if($topPhone)<h6><a href="tel:{{ preg_replace('/\s+/', '', $topPhone) }}">{{ $topPhone }}</a></h6>@endif
            @if($topEmail)<h6><a href="mailto:{{ $topEmail }}">{{ $topEmail }}</a></h6>@endif
        </div>
        @if($social->count())
            <div class="social-btn style3">
                @foreach($social as $key => $url)
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"><span class="link-effect"><span class="effect-1"><i class="{{ $socialIcons[$key] ?? 'fab fa-share-alt' }}"></i></span><span class="effect-1"><i class="{{ $socialIcons[$key] ?? 'fab fa-share-alt' }}"></i></span></span></a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="sticky-header">
    <div class="container">
        <div class="menu-area">
            <div class="row align-items-center justify-content-between thiqah-preserve-layout">
                <div class="col-auto logo"><div class="header-logo"><a href="{{ route('website.home', ['lang' => $appLocale]) }}"><img alt="{{ $companyName }}" src="{{ $logoUrl }}"><img alt="{{ $companyName }}" src="{{ $logoStickyUrl }}"></a></div></div>
                <div class="col-auto nav-menu">
                    <nav class="main-menu d-none d-lg-inline-block">
                        <ul class="navigation clearfix">
                            @foreach($headerMenuTree as $item)
                                @php $hasChildren = isset($item->children) && $item->children->count() > 0; @endphp
                                <li class="{{ $hasChildren ? 'menu-item-has-children' : '' }}">
                                    <a href="{{ $hasChildren ? '#' : $itemUrl($item) }}"{!! $hasChildren ? '' : $itemTarget($item) !!}>{{ $itemTitle($item) }}</a>
                                    @if($hasChildren)
                                        <ul class="sub-menu">
                                            @foreach($item->children as $child)
                                                <li><a href="{{ $itemUrl($child) }}"{!! $itemTarget($child) !!}>{{ $itemTitle($child) }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                    <div class="navbar-right d-inline-flex d-lg-none"><button class="menu-toggle sidebar-btn" type="button"><span class="line"></span><span class="line"></span><span class="line"></span></button></div>
                </div>
                <div class="col-auto header-right-wrapper d-none d-lg-flex">
                    <div class="header-right">
                        <div class="lang-switcher" aria-label="{{ $appLocale === 'en' ? 'Language switcher' : 'تبديل اللغة' }}">
                            <a href="{{ $switchToEnUrl }}" class="{{ $appLocale === 'en' ? 'active' : '' }}">EN</a>
                            <a href="{{ $switchToArUrl }}" class="{{ $appLocale === 'ar' ? 'active' : '' }}">AR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="search-popup">
    <button class="close-search"><i class="fa-solid fa-xmark"></i></button>
    <form method="get" action="{{ route('website.services') }}">
        <input type="hidden" name="lang" value="{{ $appLocale }}">
        <div class="form-group">
            <input id="search" type="search" name="search" placeholder="{{ $appLocale === 'en' ? 'Search...' : 'ابحث هنا...' }}" required>
            <button type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>
</div>

<div id="sidebar-area" class="sidebar">
    <button class="sidebar-close-btn">
        <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="16px" height="12.7px" viewBox="0 0 16 12.7" xml:space="preserve"><g><rect x="0" y="5.4" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -2.1569 7.5208)" width="16" height="2"></rect><rect x="0" y="5.4" transform="matrix(0.7071 0.7071 -0.7071 0.7071 6.8431 -3.7929)" width="16" height="2"></rect></g></svg>
    </button>
    <div class="sidebar-content">
        <div class="sidebar-logo"><a class="dark-logo" href="{{ route('website.home', ['lang' => $appLocale]) }}"><img src="{{ $logoUrl }}" alt="{{ $companyName }}"></a></div>
        <div class="sidebar-menu-wrap"></div>
        <div class="sidebar-about">
            <div class="sidebar-header"><h3>{{ $appLocale === 'en' ? 'About Us' : 'من نحن' }}</h3></div>
            <p>{{ $aboutShort }}</p>
            <a href="{{ route('website.contact', ['lang' => $appLocale]) }}" class="theme-btn"><span class="link-effect"><span class="effect-1">{{ $appLocale === 'en' ? 'Contact Us' : 'تواصل معنا' }}</span><span class="effect-1">{{ $appLocale === 'en' ? 'Contact Us' : 'تواصل معنا' }}</span></span></a>
        </div>
        <div class="sidebar-contact">
            <div class="sidebar-header"><h3>{{ $appLocale === 'en' ? 'Contact Us' : 'بيانات التواصل' }}</h3></div>
            <ul class="contact-info">
                <li><i class="fas fa-map-marker-alt"></i><p>{{ $address }}</p></li>
                @if($topPhone)<li><i class="fas fa-phone"></i><a href="tel:{{ preg_replace('/\s+/', '', $topPhone) }}">{{ $topPhone }}</a></li>@endif
                @if($topEmail)<li><i class="fas fa-envelope-open-text"></i><a href="mailto:{{ $topEmail }}">{{ $topEmail }}</a></li>@endif
            </ul>
        </div>
        @if($social->count())
            <ul class="sidebar-social">
                @foreach($social as $key => $url)
                    <li class="{{ $key }}"><a href="{{ $url }}"><i class="{{ $socialIcons[$key] ?? 'fab fa-share-alt' }}"></i></a></li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
