@extends('website.layout')

@php
    use Illuminate\Support\Str;

    $locale = request()->query('lang', 'ar') === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';
    $t = fn ($ar, $en) => $isEn ? $en : $ar;
    $page = $page ?? null;

    $title = $page?->{$isEn ? 'title_en' : 'title_ar'} ?: $t('من نحن', 'About us');
    $metaDesc = $page?->{$isEn ? 'meta_description_en' : 'meta_description_ar'} ?: $t('تعرّف على ثقة لتقنية نظم المعلومات وأنظمتها المؤسسية.', 'Learn about Thiqah I-Tech and our enterprise systems.');
    $cmsBody = $page ? ($isEn ? $page->content_en : $page->content_ar) : null;

    $values = [
        ['icon' => 'hm6-choose_icon01.png', 'title' => $t('وضوح', 'Clarity'), 'text' => $t('أنظمة سهلة يفهمها فريقك من أول يوم.', 'Systems your team understands from day one.')],
        ['icon' => 'hm6-choose_icon02.png', 'title' => $t('التزام', 'Commitment'), 'text' => $t('نبقى معك بعد التسليم، لا قبله فقط.', 'We stay with you after handover, not just before.')],
        ['icon' => 'hm6-choose_icon03.png', 'title' => $t('توافق', 'Compliance'), 'text' => $t('متوافقون مع أنظمة المملكة ومنصاتها.', 'Aligned with Saudi regulations and platforms.')],
    ];
    $steps = [
        ['icon' => 'icon-comercial', 'num' => '01', 'title' => $t('نفهم نشاطك', 'We understand you'), 'text' => $t('نبدأ بفهم دورة عملك قبل أي اقتراح.', 'We start by learning your workflow.')],
        ['icon' => 'icon-infomsg', 'num' => '02', 'title' => $t('نجهّز ونربط', 'We set up'), 'text' => $t('نضبط الأنظمة وننقل بياناتك ونربط المنصات.', 'We configure, migrate data and connect platforms.')],
        ['icon' => 'icon-finished', 'num' => '03', 'title' => $t('ندرّب وندعم', 'We support'), 'text' => $t('ندرّب فريقك ونتابع بعد الإطلاق.', 'We train your team and follow up after launch.')],
    ];
@endphp

@section('title', $title)
@section('meta_description', $metaDesc)

@section('content')

    @include('website.partials.page-hero', [
        'title' => $title,
        'subtitle' => $t('شريك تقني سعودي يبني أنظمة تشغيل عملية ويدعمها بعد الإطلاق.', 'A Saudi technology partner building practical operational systems and supporting them after launch.'),
    ])

    {{-- Intro / about --}}
    <section class="about-section style-6 space overflow-hidden bg-theme3">
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
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content-wrapper">
                        <div class="title-area two">
                            <div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('من نحن', 'Who we are') }}</div>
                            <h2 class="sec-title mb-25">{{ $t('نحوّل تشغيل شركتك إلى نظام واحد منظّم', 'We turn your operation into one organised system') }}</h2>
                        </div>
                        <div class="thiqah-richtext">
                            @if($cmsBody)
                                {!! $cmsBody !!}
                            @else
                                <p>{{ $t('ثقة لتقنية نظم المعلومات شركة سعودية تبني أنظمة ERP وموارد بشرية ومقاولات ومطاعم وفاتورة إلكترونية. نأخذ المنشأة من الأوراق والأنظمة المتفرقة إلى منظومة واحدة تربط الحسابات بالمخازن والمبيعات والموظفين.', 'Thiqah I-Tech is a Saudi company building ERP, HR, contracting, restaurant and e-invoicing systems. We move businesses from paper and scattered tools to one suite that links accounts, inventory, sales and staff.') }}</p>
                                <p>{{ $t('نربط أنظمتنا بمنصات مدد وسلامة وأبشر، ونلتزم بالفاتورة الإلكترونية، وندعم العميل بعد التشغيل حتى يعتمد على النظام فعلاً.', 'We connect to Mudad, Salama and Absher, comply with e-invoicing, and support the client after go-live until the system is truly relied on.') }}</p>
                            @endif
                        </div>
                        <a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-dark mt-25"><span class="link-effect"><span class="effect-1">{{ $t('تواصل معنا', 'Contact us') }}</span><span class="effect-1">{{ $t('تواصل معنا', 'Contact us') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="process-section space bg-theme3 overflow-hidden style-5">
        <div class="container">
            <div class="title-area three text-center"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('قيمنا', 'Our values') }}</div><h2 class="sec-title">{{ $t('ما الذي نلتزم به مع كل عميل', 'What we commit to with every client') }}</h2></div>
            <div class="row gy-30">
                @foreach($values as $v)
                    <div class="col-lg-4 col-md-6 wow {{ $loop->iteration === 2 ? 'fadeInRight' : 'fadeInLeft' }}">
                        <div class="thiqah-card h-100">
                            <div class="icon mb-20"><img src="{{ asset('assets/images/icons/' . $v['icon']) }}" alt="" style="width:54px;"></div>
                            <h3 style="font-size:24px;font-weight:700;margin-bottom:10px;">{{ $v['title'] }}</h3>
                            <p class="thiqah-richtext mb-0">{{ $v['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Process --}}
    <section class="process-section space-bottom bg-theme3 overflow-hidden style-5">
        <div class="container">
            <div class="title-area three text-center"><div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('كيف نعمل', 'How we work') }}</div><h2 class="sec-title">{{ $t('من أول اجتماع إلى التشغيل الكامل', 'From first meeting to full go-live') }}</h2></div>
            <div class="row gy-30">
                @foreach($steps as $step)
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

    {{-- CTA --}}
    <section class="cta-section style-4 bg-theme2 mx-30 lg-mx-0">
        <div class="bg image mbm-color-dodge"><img src="{{ asset('assets/images/cta/hm6-bg01.png') }}" alt=""></div>
        <div class="overlay"></div>
        <div class="container py-75">
            <div class="row gy-30 align-items-center">
                <div class="col-lg-7"><div class="social-proof"><div class="icon"><i class="fa-solid fa-check"></i></div><p class="text">{{ $t('ابدأ بخطوة واحدة: احجز استشارة مجانية.', 'Start with one step: book a free consultation.') }}</p></div></div>
                <div class="col-lg-5"><div class="cta-btn text-{{ $isEn ? 'right' : 'left' }} md-text-{{ $isEn ? 'left' : 'right' }}"><a href="{{ route('website.contact', ['lang' => $locale]) }}" class="theme-btn bg-dark"><span class="link-effect"><span class="effect-1">{{ $t('تواصل معنا الآن', 'Contact us now') }}</span><span class="effect-1">{{ $t('تواصل معنا الآن', 'Contact us now') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></a></div></div>
            </div>
        </div>
    </section>

@endsection
