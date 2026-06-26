@php
    use Illuminate\Support\Str;

    $locale = request()->query('lang', 'ar') === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';
    $t = fn ($ar, $en) => $isEn ? $en : $ar;

    $services = $services ?? collect();
    $aboutShort = trim((string) ($isEn
        ? (data_get($siteSettings ?? [], 'about_short_en') ?: data_get($siteSettings ?? [], 'about_short'))
        : (data_get($siteSettings ?? [], 'about_short') ?: data_get($siteSettings ?? [], 'about_short_en'))));
    $email = (string) data_get($siteSettings ?? [], 'email', '');

    $serviceTitle = fn ($s) => $isEn ? ($s->title_en ?: $s->title_ar ?: $s->code) : ($s->title_ar ?: $s->title_en ?: $s->code);
    $serviceDesc  = fn ($s) => $isEn ? (string) ($s->description_en ?? '') : (string) ($s->description_ar ?? '');
    $serviceUrl   = fn ($s) => route('website.service.show', ['code' => $s->code, 'lang' => $locale]);

    // لماذا ثقة
    $chooseItems = [
        ['icon' => 'hm6-choose_icon01.png', 'title' => $t('شريك تقني واحد', 'One technology partner'), 'text' => $t('تدير حساباتك ومخازنك وموظفيك وموقعك من جهة واحدة بدل التعامل مع عدة موردين.', 'Run your accounts, inventory, staff and website through one provider instead of juggling vendors.')],
        ['icon' => 'hm6-choose_icon02.png', 'title' => $t('أنظمة جاهزة للسوق السعودي', 'Built for the Saudi market'), 'text' => $t('فاتورة إلكترونية متوافقة مع هيئة الزكاة والضريبة، وتكامل مع مدد وسلامة وأبشر.', 'ZATCA-compliant e-invoicing and integration with Mudad, Salama and Absher.')],
        ['icon' => 'hm6-choose_icon03.png', 'title' => $t('دعم بعد التشغيل', 'Support after go-live'), 'text' => $t('فريق دعم يرد على فريقك، يدرّب المستخدمين، ويتابع النظام بعد الإطلاق.', 'A support team that answers your staff, trains users and follows up after launch.')],
    ];

    // آلية العمل
    $processSteps = [
        ['icon' => 'icon-comercial', 'num' => '01', 'title' => $t('نحلل تشغيلك', 'We map your operation'), 'text' => $t('نجلس مع فريقك ونفهم دورة العمل الحالية قبل أن نقترح أي نظام.', 'We sit with your team and learn the current workflow before proposing any system.')],
        ['icon' => 'icon-infomsg',  'num' => '02', 'title' => $t('نجهّز ونربط البيانات', 'We set up and migrate data'), 'text' => $t('نضبط الأنظمة على نشاطك، وننقل بياناتك القديمة، ونربطها بالمنصات الحكومية.', 'We configure the modules for your business, migrate your old data and connect government platforms.')],
        ['icon' => 'icon-finished', 'num' => '03', 'title' => $t('ندرّب وندعم', 'We train and support'), 'text' => $t('ندرّب المستخدمين على شاشاتهم، ونبقى معك بعد الإطلاق لحل أي ملاحظة.', 'We train each user on their screens and stay with you after launch to resolve anything.')],
    ];

    // قطاعات نخدمها (تُعرض في سلايدر المشاريع)
    $industries = [
        ['tag' => $t('مقاولات', 'Construction'), 'title' => $t('شركات المقاولات والإنشاءات', 'Construction & contracting firms'), 'text' => $t('متابعة المشاريع والمستخلصات والعهد والمخازن في نظام واحد مرتبط بالحسابات.', 'Track projects, invoices, custody and inventory in one system tied to your books.'), 'thumb' => 'project/hm6-thumb01.jpg', 'list' => [$t('إدارة مشاريع', 'Project control'), $t('مستخلصات وعقود', 'Contracts & claims'), $t('مخازن مواقع', 'Site inventory')]],
        ['tag' => $t('مطاعم', 'Restaurants'), 'title' => $t('المطاعم والكافيهات', 'Restaurants & cafés'), 'text' => $t('نقاط بيع سريعة، إدارة فروع، وربط المخزون بالمبيعات لحظة بلحظة.', 'Fast POS, multi-branch control and live stock-to-sales linkage.'), 'thumb' => 'project/hm6-thumb02.jpg', 'list' => [$t('نقاط بيع', 'POS'), $t('إدارة فروع', 'Multi-branch'), $t('تقارير مبيعات', 'Sales reports')]],
        ['tag' => $t('تجارة', 'Retail & trade'), 'title' => $t('التجارة وتجارة الجملة', 'Retail & wholesale'), 'text' => $t('مبيعات ومشتريات ومخازن وفاتورة إلكترونية في منظومة محاسبية واحدة.', 'Sales, purchasing, inventory and e-invoicing inside one accounting suite.'), 'thumb' => 'project/hm6-thumb03.jpg', 'list' => [$t('محاسبة', 'Accounting'), $t('مخازن', 'Inventory'), $t('فاتورة إلكترونية', 'E-invoicing')]],
    ];

    // تكامل حكومي
    $integrations = [
        ['title' => 'مدد', 'en' => 'Mudad', 'text' => $t('حماية أجور ومعالجة رواتب متوافقة مع منصة مدد.', 'Wage protection and payroll aligned with Mudad.')],
        ['title' => 'سلامة', 'en' => 'Salama', 'text' => $t('متابعة السلامة المهنية وربط بيانات المنشأة.', 'Occupational safety tracking and facility data sync.')],
        ['title' => 'أبشر', 'en' => 'Absher', 'text' => $t('التحقق من بيانات الموظفين عبر خدمات أبشر.', 'Employee identity verification through Absher.')],
    ];

    // آراء العملاء
    $testimonials = [
        ['title' => $t('تشغيل أوضح', 'Clearer operations'), 'rate' => '5.0', 'text' => $t('ربطنا الحسابات بالمخازن فاختفى الفرق بين الورق والواقع. صار القرار أسرع.', 'We linked accounts to inventory and the gap between paper and reality disappeared. Decisions got faster.'), 'name' => $t('م. خالد العتيبي', 'Eng. Khaled Al-Otaibi'), 'role' => $t('مدير عام، شركة مقاولات', 'GM, Contracting company'), 'img' => 'hm5-img01.jpg'],
        ['title' => $t('رواتب بلا أخطاء', 'Payroll without errors'), 'rate' => '4.9', 'text' => $t('الرواتب صارت تخرج في وقتها ومتوافقة مع مدد دون مراجعات يدوية طويلة.', 'Payroll now runs on time and Mudad-compliant without long manual reviews.'), 'name' => $t('أ. نورة الشهري', 'Noura Al-Shehri'), 'role' => $t('مديرة موارد بشرية', 'HR Manager'), 'img' => 'hm5-img02.jpg'],
        ['title' => $t('دعم يرد فعلاً', 'Support that answers'), 'rate' => '5.0', 'text' => $t('أهم نقطة عندنا الدعم. الفريق يرد، يحل، ويتابع بعد التسليم.', 'Support is what mattered to us. The team answers, fixes and follows up after handover.'), 'name' => $t('أ. سعد القحطاني', 'Saad Al-Qahtani'), 'role' => $t('صاحب سلسلة مطاعم', 'Restaurant chain owner'), 'img' => 'hm5-img01.jpg'],
    ];

    // باقات (تقديرية، يحددها العميل لاحقًا)
    $plans = [
        ['name' => $t('الأساسية', 'Starter'), 'price' => '٤٩٩', 'priceEn' => '499', 'current' => false, 'features' => [
            [$t('محاسبة ومخازن', 'Accounting & inventory'), true],
            [$t('فاتورة إلكترونية', 'E-invoicing'), true],
            [$t('مستخدم حتى ٣ حسابات', 'Up to 3 users'), true],
            [$t('موارد بشرية ورواتب', 'HR & payroll'), false],
            [$t('تكامل حكومي', 'Government integration'), false],
        ]],
        ['name' => $t('الاحترافية', 'Professional'), 'price' => '٩٩٩', 'priceEn' => '999', 'current' => true, 'features' => [
            [$t('محاسبة ومخازن', 'Accounting & inventory'), true],
            [$t('فاتورة إلكترونية', 'E-invoicing'), true],
            [$t('موارد بشرية ورواتب', 'HR & payroll'), true],
            [$t('تكامل مدد وسلامة', 'Mudad & Salama integration'), true],
            [$t('دعم ذو أولوية', 'Priority support'), false],
        ]],
        ['name' => $t('المؤسسية', 'Enterprise'), 'price' => $t('حسب الطلب', 'Custom'), 'priceEn' => $t('حسب الطلب', 'Custom'), 'current' => false, 'features' => [
            [$t('كل مزايا الاحترافية', 'Everything in Professional'), true],
            [$t('إدارة مقاولات أو مطاعم', 'Construction or restaurant module'), true],
            [$t('مستخدمون بلا حد', 'Unlimited users'), true],
            [$t('تكامل أبشر', 'Absher integration'), true],
            [$t('دعم مخصص', 'Dedicated support'), true],
        ]],
    ];
@endphp

@extends('website.layout')

@section('title', $t('ثقة لتقنية نظم المعلومات', 'Thiqah I-Tech'))
@section('meta_description', $t('أنظمة ERP وموارد بشرية ومقاولات ومطاعم وفاتورة إلكترونية متوافقة مع السوق السعودي.', 'ERP, HR, contracting, restaurant and e-invoicing systems built for the Saudi market.'))

@section('content')

    {{-- ===== Hero ===== --}}
    @include('website.partials.hero-slider', ['heroSlider' => $heroSlider ?? null, 'locale' => $locale])

    {{-- ===== About ===== --}}
    <section class="about-section style-6 space overflow-hidden bg-theme3" id="about-section">
        <div class="container">
            <div class="row gy-50 align-items-center">
                <div class="col-lg-6">
                    <div class="about-thumb-area mr-60 xl-mr-0">
                        <div class="about-slider swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><div class="about-slide_thumb"><img src="{{ asset('assets/images/about/hm6-img01.jpg') }}" alt=""></div></div>
                                <div class="swiper-slide"><div class="about-slide_thumb"><img src="{{ asset('assets/images/service/hm6-img01.jpg') }}" alt=""></div></div>
                            </div>
                            <div class="array-button"><button class="array-prev"><i class="fa-light fa-arrow-left-long"></i></button><button class="array-next active"><i class="fa-light fa-arrow-right-long"></i></button></div>
                        </div>
                        <div class="customar-box">
                            <div class="box-top"><div class="awards"><span class="count-number odometer" data-count="{{ max($services->count(), 8) }}">0</span><span class="plus">+</span></div><div class="icon"><i class="fa-solid fa-circle-check"></i></div></div>
                            <div class="box-bottom"><h6>{{ $t('نظام وخدمة جاهزة للتشغيل', 'Modules ready to run') }}</h6></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content-wrapper">
                        <div class="title-area two">
                            <div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('عن ثقة', 'About Thiqah') }}</div>
                            <h2 class="sec-title mb-25">{{ $t('نبني الأنظمة التي تدير أعمالك يومًا بيوم', 'We build the systems that run your business day to day') }}</h2>
                            <p class="sec-text text-gray">{{ $aboutShort !== '' ? Str::limit(strip_tags($aboutShort), 240) : $t('نطوّر أنظمة ERP وموارد بشرية ومقاولات ومطاعم تناسب بيئة العمل السعودية، ونربطها بالمنصات الحكومية، وندعمها بعد التشغيل.', 'We build ERP, HR, contracting and restaurant systems for the Saudi work environment, connect them to government platforms and support them after go-live.') }}</p>
                        </div>
                        <div class="feature-list">
                            <div class="feature-item"><div class="icon"><i class="flaticon-service"></i></div><p>{{ $t('حسابات ومخازن وفاتورة إلكترونية', 'Accounting, inventory & e-invoicing') }}</p></div>
                            <div class="feature-item"><div class="icon"><i class="flaticon-people"></i></div><p>{{ $t('موارد بشرية ورواتب وتكامل حكومي', 'HR, payroll & government integration') }}</p></div>
                        </div>
                        <div class="pt-35 pb-25"><div class="border"><span class="bar"></span></div></div>
                        <ul class="features-list">
                            <li>{{ $t('رؤية واحدة للحسابات والمشاريع والفرق', 'One view over accounts, projects and teams') }}</li>
                            <li>{{ $t('أنظمة تتوسع مع نشاطك خطوة بخطوة', 'Modules that scale with your business') }}</li>
                        </ul>
                        <a href="{{ route('website.about', ['lang' => $locale]) }}" class="theme-btn bg-dark mt-35"><span class="link-effect"><span class="effect-1">{{ $t('المزيد عنا', 'More about us') }}</span><span class="effect-1">{{ $t('المزيد عنا', 'More about us') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-top-right wow slideInRight"><img src="{{ asset('assets/images/choose/shape01.png') }}" alt=""></div>
        <div class="p-bottom-right wow img-anim-right"><img src="{{ asset('assets/images/about/hm6-about-line.png') }}" alt=""></div>
    </section>

    {{-- ===== Solutions (feature) ===== --}}
    <section class="feature-section space bg-dark style-5 mx-30 lg-mx-0">
        <div class="container">
            <div class="row"><div class="col-lg-12">
                <div class="feature-title-area d-flex justify-content-between sm-flex-column sm-mb-30">
                    <div class="title-area white three">
                        <div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('حلولنا', 'Our solutions') }}</div>
                        <h2 class="sec-title mb-0">{{ $t('حلول تحافظ على تشغيل أعمالك بلا توقف', 'Solutions that keep your operation running') }}</h2>
                    </div>
                    <div class="service-btn sm-justify-content-start"><a href="{{ route('website.services', ['lang' => $locale]) }}" class="theme-btn bg-color10"><span class="link-effect"><span class="effect-1">{{ $t('كل الأنظمة', 'All modules') }}</span><span class="effect-1">{{ $t('كل الأنظمة', 'All modules') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div>
                </div>
            </div></div>
            <div class="row gy-30">
                @foreach($services->take(3) as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-box-four dark {{ $loop->iteration === 2 ? 'current' : '' }}">
                            <div class="inner">
                                <div class="image-box"><div class="thumb"><img src="{{ asset('assets/images/service/hm5-img0' . min($loop->iteration, 3) . '.jpg') }}" alt=""></div><div class="service-icon"><img src="{{ asset('assets/images/icons/hm6-icon0' . min($loop->iteration, 4) . '.png') }}" alt=""></div></div>
                                <div class="content"><h4 class="title"><a href="{{ $serviceUrl($service) }}">{{ $serviceTitle($service) }}</a></h4><p class="text">{{ Str::limit(strip_tags($serviceDesc($service)), 120) }}</p><a href="{{ $serviceUrl($service) }}" class="service-btn">{{ $t('اعرف المزيد', 'Learn more') }} <i class="fa fa-arrow-right-long"></i></a></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Why choose (choose) ===== --}}
    <section class="choose-section style-6 bg-theme3 space overflow-hidden">
        <div class="container-fluid px-150 xxl-px-80 lg-px-30 md-px-15">
            <div class="row gy-30">
                <div class="col-lg-4 col-xxl-4">
                    <div class="choose-content-wrapper">
                        <div class="title-area twoT">
                            <div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('لماذا ثقة', 'Why Thiqah') }}</div>
                            <h2 class="sec-title">{{ $t('نظام واحد يربط فرعك ومخزنك وفريقك', 'One system linking your branch, stock and team') }}</h2>
                            <p class="sec-text text-gray">{{ $t('نأخذك من جداول الإكسل والأنظمة المتفرقة إلى منظومة واحدة تتكلم مع بعضها، وتعطيك أرقامًا تثق بها.', 'We move you from scattered spreadsheets to one connected suite that gives you numbers you can trust.') }}</p>
                        </div>
                        <ul class="features-list">
                            <li>{{ $t('بيانات واحدة بلا تكرار', 'Single source of data') }}</li>
                            <li>{{ $t('صلاحيات واضحة لكل مستخدم', 'Clear permissions per user') }}</li>
                        </ul>
                        <a href="{{ route('website.about', ['lang' => $locale]) }}" class="theme-btn bg-dark mt-35"><span class="link-effect"><span class="effect-1">{{ $t('المزيد عنا', 'More about us') }}</span><span class="effect-1">{{ $t('المزيد عنا', 'More about us') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xxl-4 md-d-none">
                    <div class="choose-image-wrapper">
                        <div class="thumb-bg"><img src="{{ asset('assets/images/choose/hm6-bg01.png') }}" alt=""></div>
                        <div class="thumb"><img src="{{ asset('assets/images/choose/hm6-img01.png') }}" alt=""></div>
                    </div>
                </div>
                <div class="col-lg-4 col-xxl-4">
                    <div class="choose-right-wrapper">
                        <div class="right-top">
                            <div class="icon"><i class="fa-solid fa-check"></i></div>
                            <h4 class="text">{{ $t('نلتزم بتشغيل أعمالك على أعلى مستوى', 'Committed to running your business right') }}</h4>
                        </div>
                        <div class="py-40"><div class="border"></div></div>
                        @foreach($chooseItems as $c)
                            <div class="featured-box {{ !$loop->last ? 'mb-45' : '' }}">
                                <div class="icon"><img src="{{ asset('assets/images/icons/' . $c['icon']) }}" alt=""></div>
                                <div class="content"><h4>{{ $c['title'] }}</h4><p>{{ $c['text'] }}</p></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="wrapper-section br-20 lg-br-0 overflow-hidden mx-30 lg-mx-0">
        {{-- Marquee --}}
        <div class="marquee-section style-3">
            <div class="bg image"><img src="{{ asset('assets/images/marquee/hm5-bg01.jpg') }}" alt=""></div>
            <div class="container-fluid p-0 overflow-hidden">
                <div class="slider__marquee clearfix marquee-wrap">
                    <ul class="marquee_mode marquee__group">
                        @php $words = [$t('أنظمة ERP','ERP Systems'), $t('موارد بشرية','HR & Payroll'), $t('فاتورة إلكترونية','E-Invoicing'), $t('إدارة مقاولات','Construction'), $t('نقاط بيع','POS'), $t('استضافة ودومينات','Hosting & Domains')]; @endphp
                        @foreach(array_merge($words, $words) as $w)
                            <li class="item m-item"><img class="icon" src="{{ asset('assets/images/marquee/hm5-star01.png') }}" alt="">{{ $w }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Video + stats --}}
        <div class="video-section bg-theme3 style-3 position-relative z-1" id="video">
            <div class="container-fluid">
                <div class="row"><div class="col-lg-12 p-0">
                    <div class="video-area position-relative">
                        <div class="video-box"><a class="popup-video play-btn style-2" href="https://www.youtube.com/watch?v=SMKPKGW083c" data-fancybox="video-gallery"><i class="fa-sharp fa-solid fa-play"></i></a></div>
                        <div class="thumb"><img class="mw-inherit" src="{{ asset('assets/images/video/hm6-thumb01.jpg') }}" alt=""></div>
                    </div>
                </div></div>
            </div>
            <div class="stats-container">
                <div class="stat-box bg-theme br_tl-10 white"><div class="count-box"><span class="count-number odometer" data-count="12">0</span>+</div><p class="text">{{ $t('سنة خبرة في السوق', 'Years in the market') }}</p></div>
                <div class="stat-box bg-theme2 br_br-10 dark"><div class="count-box"><span class="count-number odometer" data-count="350">0</span>+</div><p class="text">{{ $t('منشأة تشغّل أنظمتنا', 'Businesses on our systems') }}</p></div>
            </div>
        </div>

        {{-- Services list --}}
        <section class="service-section style-6 bg-white space overflow-hidden">
            <div class="container">
                <div class="row"><div class="col-lg-12">
                    <div class="service-title-area d-flex justify-content-between sm-flex-column sm-mb-30">
                        <div class="title-area dark three"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('أنظمتنا', 'Our modules') }}</div><h2 class="sec-title mb-0">{{ $t('كل ما تحتاجه لإدارة نشاطك', 'Everything you need to run your business') }}</h2></div>
                        <div class="service-btn sm-justify-content-start"><a href="{{ route('website.services', ['lang' => $locale]) }}" class="theme-btn bg-transparent"><span class="link-effect"><span class="effect-1">{{ $t('كل الأنظمة', 'All modules') }}</span><span class="effect-1">{{ $t('كل الأنظمة', 'All modules') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div>
                    </div>
                </div></div>
                <div class="row"><div class="col-lg-12"><div class="service-wrapper">
                    @forelse($services->take(6) as $service)
                        <div class="service-single-item">
                            <div class="item-left"><div class="image"><img src="{{ asset('assets/images/service/hm6-img01.jpg') }}" alt=""></div><div class="item-wrap"><div class="icon">@if(!empty($service->icon_class))<i class="{{ $service->icon_class }}"></i>@else<img src="{{ asset('assets/images/icons/hm6-icon0' . (($loop->iteration % 4) + 1) . '.png') }}" alt="">@endif</div><h3 class="text"><a href="{{ $serviceUrl($service) }}">{{ $serviceTitle($service) }}</a></h3></div></div>
                            <div class="item-right"><div class="item-right-inner"><p>{{ Str::limit(strip_tags($serviceDesc($service)), 150) }}</p><a href="{{ $serviceUrl($service) }}" class="icon"><i class="fa-solid fa-angle-{{ $isEn ? 'right' : 'left' }}"></i></a></div></div>
                        </div>
                    @empty
                        <div class="service-single-item"><div class="item-left"><div class="item-wrap"><h3 class="text">{{ $t('لا توجد أنظمة مضافة بعد', 'No modules added yet') }}</h3></div></div></div>
                    @endforelse
                </div></div></div>
            </div>
        </section>
    </div>

    {{-- ===== Industries (project slider) ===== --}}
    <section class="project-section style-6 space-top bg-theme3 mx-30 lg-mx-0">
        <div class="container">
            <div class="row"><div class="col-lg-12">
                <div class="project-title-area">
                    <div class="title-area dark three"><div class="sub-title text-dark"><span><i class="asterisk"></i></span>{{ $t('قطاعات نخدمها', 'Industries we serve') }}</div><h2 class="sec-title mb-0">{{ $t('أنظمة مفصّلة على طبيعة نشاطك', 'Systems shaped around your industry') }}</h2></div>
                    <div class="project-btn-wrapper"><div class="array-button"><button class="array-prev"><i class="fa fa-arrow-left-long"></i></button><button class="array-next active"><i class="fa fa-arrow-right-long"></i></button></div></div>
                </div>
            </div></div>
        </div>
        <div class="project-slider swiper z-1 position-relative">
            <div class="swiper-wrapper">
                @foreach($industries as $ind)
                    <div class="swiper-slide">
                        <div class="project-wrapper position-relative z-1">
                            <div class="project-thum"><img src="{{ asset('assets/images/' . $ind['thumb']) }}" alt=""></div>
                            <div class="project-content position-relative z-1">
                                <div class="project-box-title"><p class="sub-title">{{ $ind['tag'] }}</p><h2 class="title">{{ $ind['title'] }}</h2></div>
                                <p>{{ $ind['text'] }}</p>
                                <ul class="project-list">
                                    @foreach($ind['list'] as $li)<li><i class="fa-solid fa-check"></i>{{ $li }}</li>@endforeach
                                </ul>
                                <div class="pt-35 pb-35"><div class="border white"><span class="bar"></span></div></div>
                                <div class="project-btn"><a href="{{ route('website.services', ['lang' => $locale]) }}" class="theme-btn bg-theme2"><span class="link-effect"><span class="effect-1">{{ $t('التفاصيل', 'View details') }}</span><span class="effect-1">{{ $t('التفاصيل', 'View details') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div>
                                <div class="p-top-right wow slideInRight"><img src="{{ asset('assets/images/project/hm6-project-shape.png') }}" alt=""></div>
                                <div class="p-bottom-right wow img-anim-right"><img src="{{ asset('assets/images/project/hm6-project-line.png') }}" alt=""></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Process ===== --}}
    <section class="process-section space bg-theme3 overflow-hidden style-5">
        <div class="container">
            <div class="title-area three text-center"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('خطوات بسيطة', 'Simple process') }}</div><h2 class="sec-title">{{ $t('كيف نطبّق النظام معك', 'How we roll out your system') }}</h2></div>
            <div class="row gy-30">
                @foreach($processSteps as $step)
                    <div class="col-lg-4 col-md-6 col-sm-6 wow {{ $loop->iteration === 2 ? 'fadeInRight' : 'fadeInLeft' }}">
                        <div class="process-single-box br-10 {{ $loop->iteration === 2 ? 'current' : '' }}">
                            <div class="inner-box">
                                <div class="header"><div class="icon"><i class="{{ $step['icon'] }}"></i></div><h4 class="title m-0">{{ $step['title'] }}</h4></div>
                                <p class="text">{{ $step['text'] }}</p>
                                <div class="box-footer"><div class="box-count"><span>{{ $step['num'] }}</span></div></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="wrapper-section br-20 lg-br-0 overflow-hidden mx-30 lg-mx-0 mb-30 md-mb-0">
        {{-- ===== Government integrations ===== --}}
        <section class="team-section style-4 space bg-white">
            <div class="container">
                <div class="row"><div class="col-lg-12">
                    <div class="team-title-area mb-60 md-mb-40">
                        <div class="title-area mb-0"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('تكامل حكومي', 'Government integration') }}</div><h2 class="sec-title mb-0">{{ $t('أنظمتنا متصلة بمنصات المملكة', 'Connected to Saudi government platforms') }}</h2></div>
                        <div class="team-btn d-flex align-items-center"><a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-dark"><span class="link-effect"><span class="effect-1">{{ $t('اطلب التفاصيل', 'Ask for details') }}</span><span class="effect-1">{{ $t('اطلب التفاصيل', 'Ask for details') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div>
                    </div>
                </div></div>
                <div class="row gy-30">
                    @foreach($integrations as $intg)
                        <div class="col-lg-4 col-md-6">
                            <div class="thiqah-card h-100 text-center">
                                <div class="icon mb-20" style="font-size:42px;color:#ff9800;"><i class="fa-solid fa-shield-halved"></i></div>
                                <h3 style="font-size:26px;font-weight:700;margin-bottom:6px;">{{ $intg['title'] }}</h3>
                                <span style="display:block;color:#98a2b3;margin-bottom:14px;letter-spacing:.05em;">{{ $intg['en'] }}</span>
                                <p class="thiqah-richtext mb-0">{{ $intg['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===== CTA ===== --}}
        <section class="cta-section style-4 bg-theme2">
            <div class="bg image mbm-color-dodge"><img src="{{ asset('assets/images/cta/hm6-bg01.png') }}" alt=""></div>
            <div class="overlay"></div>
            <div class="container py-75">
                <div class="row gy-30 align-items-center">
                    <div class="col-lg-7"><div class="social-proof"><div class="icon"><i class="fa-solid fa-check"></i></div><p class="text">{{ $t('جاهز تنقل تشغيل شركتك إلى نظام واحد منظّم؟', 'Ready to move your operation onto one organised system?') }}</p></div></div>
                    <div class="col-lg-5"><div class="cta-btn text-{{ $isEn ? 'right' : 'left' }} md-text-{{ $isEn ? 'left' : 'right' }}"><a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-dark"><span class="link-effect"><span class="effect-1">{{ $t('تواصل معنا الآن', 'Contact us now') }}</span><span class="effect-1">{{ $t('تواصل معنا الآن', 'Contact us now') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div></div>
                </div>
            </div>
        </section>
    </div>

    {{-- ===== Testimonials ===== --}}
    <section class="testimonial-section style-6 space overflow-hidden bg-dark mx-30 lg-mx-0 br-20 lg-br-0">
        <div class="shape-mockup jump" data-bottom="70px" data-left="35%"><img src="{{ asset('assets/images/testimonial/hm6-dotshape.png') }}" alt=""></div>
        <div class="container">
            <div class="row gy-30">
                <div class="col-lg-4 col-md-6">
                    <div class="testi-content-wrap">
                        <div class="title-area white two"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('آراء العملاء', 'Testimonials') }}</div><h2 class="sec-title">{{ $t('ماذا يقول عملاؤنا عن العمل معنا', 'What our clients say about working with us') }}</h2></div>
                        <div class="testi-clutch mb-35"><div class="review-card-three"><span class="avarage-rating text-theme2">4.9</span><div class="rating-inner"><span class="rating-text text-white">{{ $t('متوسط التقييم', 'Average rating') }}</span><span class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-stroke"></i></span></div></div></div>
                        <div class="testi-btn-wrapper"><div class="array-button"><button class="array-prev"><i class="fa fa-arrow-left-long"></i></button><button class="array-next active"><i class="fa fa-arrow-right-long"></i></button></div></div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="testi-slider-6 swiper">
                        <div class="swiper-wrapper">
                            @foreach($testimonials as $ts)
                                <div class="swiper-slide">
                                    <div class="testimonial-card-five style-6">
                                        <div class="inner-box">
                                            <div class="content">
                                                <h4 class="title"><i class="fa-solid fa-quote-left"></i> {{ $ts['title'] }}</h4>
                                                <div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><span>{{ $ts['rate'] }}</span></div>
                                                <p class="text">{{ $ts['text'] }}</p>
                                            </div>
                                            <div class="border mt-35 mb-40"></div>
                                            <div class="user-info"><img src="{{ asset('assets/images/testimonial/' . $ts['img']) }}" alt="" class="user-image"><div><h5 class="user-name">{{ $ts['name'] }}</h5><p class="user-title">{{ $ts['role'] }}</p></div></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Pricing ===== --}}
    <section class="pricing-section style-3 space bg-theme3">
        <div class="p-top-right wow slideInRight"><img src="{{ asset('assets/images/choose/shape01.png') }}" alt=""></div>
        <div class="p-bottom-left wow img-anim-left"><img src="{{ asset('assets/images/pricing/hm6-pricing-line.png') }}" alt=""></div>
        <div class="container">
            <div class="row gy-30">
                <div class="col-lg-6">
                    <div class="pricing-content-wrapper">
                        <div class="title-area"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('باقات الاشتراك', 'Pricing plans') }}</div><h2 class="sec-title">{{ $t('باقات واضحة تبدأ بما يناسب حجمك', 'Clear plans that start at your size') }}</h2><p class="sec-text text-gray">{{ $t('اختر الباقة التي تناسب نشاطك اليوم، وارفعها متى ما كبر فريقك. الأسعار تقديرية وتُحدد بدقة بعد فهم احتياجك.', 'Pick the plan that fits today and upgrade as your team grows. Prices are indicative and confirmed after we scope your needs.') }}</p></div>
                        <a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-dark"><span class="link-effect"><span class="effect-1">{{ $t('اطلب عرض سعر', 'Request a quote') }}</span><span class="effect-1">{{ $t('اطلب عرض سعر', 'Request a quote') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    @foreach($plans as $plan)
                        <div class="pricing-single-box {{ $plan['current'] ? 'current' : '' }} mb-20">
                            <div class="pricing-box-left">
                                <div class="pricing-title">
                                    <p>{{ $plan['name'] }}</p>
                                    @php $priceVal = $isEn ? $plan['priceEn'] : $plan['price']; @endphp
                                    @if($priceVal === $t('حسب الطلب', 'Custom'))
                                        <h2 style="font-size:30px;">{{ $priceVal }}</h2>
                                    @else
                                        <h2>{{ $priceVal }}<sub>{{ $t('ريال/شهريًا', 'SAR/mo') }}</sub></h2>
                                    @endif
                                </div>
                                <a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-theme3"><span class="link-effect"><span class="effect-1">{{ $t('ابدأ الآن', 'Get started') }}</span><span class="effect-1">{{ $t('ابدأ الآن', 'Get started') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                            </div>
                            <div class="pricing-box-right">
                                <ul class="pricing-list">
                                    @foreach($plan['features'] as $f)
                                        <li class="{{ $f[1] ? '' : 'color-0' }}"><i class="fa-regular fa-circle-{{ $f[1] ? 'check' : 'xmark' }}"></i>{{ $f[0] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="wrapper-section br-20 overflow-hidden mx-30 lg-mx-0">
        {{-- ===== Contact / Appointment ===== --}}
        <section class="contact-section style-3 overflow-hidden space-top">
            <div class="bg image"><img src="{{ asset('assets/images/contact/hm6-bg01.jpg') }}" alt=""></div>
            <div class="overlay"></div>
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-xl-7 col-lg-6">
                        <div class="contact-content mb-120">
                            <div class="title-area white two"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('استشارة', 'Consultation') }}</div><h2 class="sec-title">{{ $t('احجز استشارة مجانية لاختيار النظام المناسب', 'Book a free consultation to pick the right system') }}</h2><div class="text"><div class="icon"><i class="fa-solid fa-check"></i></div><p>{{ $t('نرد عليك ونقترح أنسب باقة لنشاطك.', 'We get back to you and suggest the best fit for your business.') }}</p></div></div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6">
                        <div class="appointment-area">
                            <div class="header"><h2 class="title">{{ $t('تواصل معنا', 'Get in touch') }}</h2><span class="availability mb-25">{{ $t('املأ النموذج وسنعاود الاتصال بك.', 'Fill the form and we will reach out.') }}</span></div>
                            <form id="appointment_form" class="appointment-form" action="{{ route('website.contact.submit', ['lang' => $locale]) }}" method="post">
                                @csrf
                                <div class="form-group"><span class="icon"><i class="fa-solid fa-user"></i></span><input type="text" name="name" placeholder="{{ $t('الاسم', 'Your name') }}" required></div>
                                <div class="form-group"><span class="icon"><i class="fa-regular fa-envelope"></i></span><input type="email" name="email" placeholder="{{ $t('البريد الإلكتروني', 'Email address') }}" required></div>
                                <div class="form-group"><span class="icon"><i class="fa-solid fa-phone"></i></span><input type="text" name="phone" placeholder="{{ $t('رقم الجوال', 'Phone') }}"></div>
                                <div class="form-group"><span class="icon"><i class="fa-solid fa-building"></i></span><input type="text" name="company" placeholder="{{ $t('اسم الشركة', 'Company') }}"></div>
                                <div class="form-group mb-15"><textarea name="message" placeholder="{{ $t('النظام الذي تهتم به أو سؤالك', 'Which system interests you, or your question') }}" required></textarea></div>
                                <button type="submit" class="theme-btn bg-theme" data-loading-text="{{ $t('جارٍ الإرسال...', 'Please wait...') }}"><span class="link-effect"><span class="btn-title">{{ $t('إرسال الطلب', 'Send request') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== Trusted bar ===== --}}
        <div class="brands-section space bg-white">
            <div class="container"><div class="row"><div class="col-lg-12">
                <div class="sponsors-outer">
                    <div class="trusted-partners mt--15"><span class="bg-white pr-10">{{ $t('يثقون بنا', 'Trusted by') }}</span></div>
                    <div class="brands-slider swiper"><div class="swiper-wrapper">
                        @foreach(['01','02','03','04','05','06','01'] as $b)
                            <div class="swiper-slide"><div class="brand-item"><a class="image" href="#"><img src="{{ asset('assets/images/brands/' . $b . '.png') }}" alt=""><img src="{{ asset('assets/images/brands/' . $b . '.png') }}" alt=""></a></div></div>
                        @endforeach
                    </div></div>
                    <div class="trusted-partners text-{{ $isEn ? 'right' : 'left' }} mb--10"><span class="bg-white pl-10">{{ $t('منشآت في مختلف القطاعات', 'Businesses across sectors') }}</span></div>
                </div>
            </div></div></div>
        </div>
    </div>

    {{-- ===== Contact wrapper ===== --}}
    <div class="contact-wrapper space-bottom bg-theme3">
        <div class="contact-option"><i class="fa-regular fa-envelope"></i><span>{{ $t('تحتاج مساعدة؟', 'Looking for help?') }}</span><a href="{{ route('website.contact', ['lang' => $locale]) }}" class="contact-link">{{ $t('تواصل معنا اليوم', 'Contact us today') }}</a></div>
        <div class="social-option"><i class="fa-regular fa-thumbs-up"></i><span>{{ $t('تعرّف على حلولنا', 'Discover our solutions') }}</span><a href="{{ route('website.services', ['lang' => $locale]) }}" class="social-link">{{ $t('استعرض كل الأنظمة', 'Browse all modules') }}</a></div>
    </div>

@endsection
