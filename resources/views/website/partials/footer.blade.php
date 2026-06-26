@php
    use App\Models\Menu;
    use Illuminate\Support\Str;

    $ss = fn ($key, $default = null) => data_get($siteSettings ?? [], $key, $default);
    $appLocale = $appLocale ?? request()->query('lang', 'ar');
    $appLocale = strtolower((string) $appLocale) === 'en' ? 'en' : 'ar';

    $companyName = $appLocale === 'en' ? $ss('company_name_en', 'Thiqah') : $ss('company_name', 'ثقة');
    $aboutShort = trim((string) ($appLocale === 'en' ? $ss('about_short_en', '') : $ss('about_short', '')));
    $email = (string) $ss('email', '');
    $phone = (string) $ss('phone', '');
    $logo = $ss('company_logo') ?: $ss('logo');
    $logoUrl = $logo ? (str_starts_with($logo, 'http') ? $logo : asset('storage/' . ltrim(preg_replace('#^storage/#', '', $logo), '/'))) : asset('assets/images/logo/logo-2.png');

    $footerMenu = Menu::query()->where('code', 'footer')->where('is_active', 1)->with([
        'items' => fn ($q) => $q->whereNull('parent_id')->where('is_active', 1)->orderBy('order'),
        'items.page',
        'items.children' => fn ($q) => $q->where('is_active', 1)->orderBy('order'),
        'items.children.page',
    ])->first();

    $columns = $footerMenu?->items ?? collect();
    $itemTitle = fn ($item) => $appLocale === 'en' ? ($item->title_en ?: $item->title_ar) : ($item->title_ar ?: $item->title_en);
    $itemUrl = function ($item) use ($appLocale) {
        if (!empty($item->route_name) && \Route::has($item->route_name)) return route($item->route_name, ['lang' => $appLocale]);
        if (($item->type ?? '') === 'page' && !empty($item->page?->slug)) return route('website.page', ['slug' => $item->page->slug, 'lang' => $appLocale]);
        $url = trim((string) ($item->url ?? ''));
        if ($url === '') return '#';
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, '#') || str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:')) return $url;
        return url('/' . ltrim($url, '/')) . '?lang=' . $appLocale;
    };

    $socialMap = ['facebook' => 'FB.', 'twitter' => 'TW.', 'linkedin' => 'LN.', 'instagram' => 'IG.', 'youtube' => 'YT.', 'whatsapp' => 'WA.'];
    $socialLinks = collect($socialLinks ?? [])->filter();
@endphp

<section class="newsletter-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="newsletter">
                    <div class="p-top-left wow slideInLeft"><img src="{{ asset('assets/images/newslatter/shape01.png') }}" alt=""></div>
                    <div class="p-top-right wow slideInRight"><img src="{{ asset('assets/images/newslatter/shape02.png') }}" alt=""></div>
                    <div class="text"><h3>{{ $appLocale === 'en' ? 'Need a free consultation?' : 'هل تحتاج إلى استشارة مجانية؟' }}</h3></div>
                    <div class="contact-info">
                        <div class="email-icon"><i class="fa-regular fa-envelope"></i></div>
                        <div class="email-details"><p>{{ $appLocale === 'en' ? 'Send e-mail' : 'راسلنا عبر البريد' }}</p><a href="mailto:{{ $email ?: 'info@example.com' }}">{{ $email ?: 'info@example.com' }}</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer-section bg-dark">
    <div class="footer-top space">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-6 footer-brand">
                    <div class="brand-header">
                        <a href="{{ route('website.home', ['lang' => $appLocale]) }}" class="footer-logo d-block mb-20"><img src="{{ $logoUrl }}" alt="{{ $companyName }}"></a>
                        <p class="text">{{ $aboutShort !== '' ? Str::limit($aboutShort, 120) : ($appLocale === 'en' ? 'Integrated software solutions that simplify operations and support business growth.' : 'حلول برمجية متكاملة تبسط التشغيل وتدعم نمو الأعمال.') }}</p>
                        @if($phone)<p class="text mt-3">{{ $phone }}</p>@endif
                        @if($email)<p class="text">{{ $email }}</p>@endif
                    </div>
                    @if($socialLinks->count())
                        <div class="footer-social">
                            @foreach($socialLinks as $key => $url)
                                <a href="{{ $url }}" class="social-link" target="_blank" rel="noopener noreferrer">{{ $socialMap[$key] ?? strtoupper(substr($key, 0, 2)) . '.' }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-5 col-md-7">
                    <div class="row">
                        @forelse($columns->take(2) as $column)
                            <div class="col-lg-6 col-md-6 p-0 sm-pl-15">
                                <div class="footer-widget">
                                    <h4 class="title">{{ $itemTitle($column) }}</h4>
                                    <ul class="list-unstyled">
                                        @forelse(($column->children ?? collect()) as $child)
                                            <li><a href="{{ $itemUrl($child) }}">{{ $itemTitle($child) }}</a></li>
                                        @empty
                                            <li><a href="#">{{ $appLocale === 'en' ? 'Coming soon' : 'سيضاف قريبًا' }}</a></li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-6 col-md-6 p-0 sm-pl-15">
                                <div class="footer-widget">
                                    <h4 class="title">{{ $appLocale === 'en' ? 'Company' : 'الشركة' }}</h4>
                                    <ul class="list-unstyled">
                                        <li><a href="{{ route('website.about', ['lang' => $appLocale]) }}">{{ $appLocale === 'en' ? 'About us' : 'من نحن' }}</a></li>
                                        <li><a href="{{ route('website.services', ['lang' => $appLocale]) }}">{{ $appLocale === 'en' ? 'Services' : 'الخدمات' }}</a></li>
                                        <li><a href="{{ route('website.contact', ['lang' => $appLocale]) }}">{{ $appLocale === 'en' ? 'Contact' : 'تواصل معنا' }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="footer-widget ml-0 mb-0">
                        <h4 class="title">{{ $appLocale === 'en' ? 'Quick Contact' : 'تواصل سريع' }}</h4>
                        <p class="text">{{ $appLocale === 'en' ? 'Leave your email and let us reach out with the right solution.' : 'اترك بريدك وسنتواصل معك بالحل المناسب لنشاطك.' }}</p>
                        <form class="newsletter-form" action="{{ route('website.contact', ['lang' => $appLocale]) }}" method="get">
                            <div class="form-group">
                                <input type="email" name="email" class="email" placeholder="{{ $appLocale === 'en' ? 'Email Address' : 'البريد الإلكتروني' }}" autocomplete="on">
                                <button type="submit"><i class="far fa-paper-plane"></i><span class="btn-title"></span></button>
                            </div>
                        </form>
                        <div class="notify"><div class="icon"><i class="fa-regular fa-bell"></i></div>{{ $appLocale === 'en' ? 'We respond quickly to new inquiries.' : 'نرد سريعًا على الاستفسارات الجديدة.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6"><p class="mb-0">&copy;{{ date('Y') }} {{ $companyName }} - {{ $appLocale === 'en' ? 'All Rights Reserved.' : 'جميع الحقوق محفوظة.' }}</p></div>
                <div class="col-md-6 text-md-end"><div class="footer-policy"><a href="{{ route('website.home', ['lang' => $appLocale]) }}">{{ $appLocale === 'en' ? 'Home' : 'الرئيسية' }}</a><a href="{{ route('website.services', ['lang' => $appLocale]) }}">{{ $appLocale === 'en' ? 'Services' : 'الخدمات' }}</a><a href="{{ route('website.contact', ['lang' => $appLocale]) }}">{{ $appLocale === 'en' ? 'Contact' : 'التواصل' }}</a></div></div>
            </div>
        </div>
    </div>
</footer>
