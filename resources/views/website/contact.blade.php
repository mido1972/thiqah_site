@extends('website.layout')

@php
    $locale = request()->query('lang', 'ar') === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';
    $t = fn ($ar, $en) => $isEn ? $en : $ar;
    $page = $page ?? null;

    $ss = fn ($key, $default = null) => data_get($siteSettings ?? [], $key, $default);
    $phone    = $ss('phone');
    $whatsapp = $ss('whatsapp');
    $email    = $ss('email');
    $address  = $isEn ? ($ss('address_en') ?: $ss('address')) : ($ss('address') ?: $ss('address_en'));
    $mapEmbed = $ss('google_map_embed');

    $pageTitle = $page?->{$isEn ? 'title_en' : 'title_ar'} ?: $t('تواصل معنا', 'Contact us');
    $metaTitle = $page?->{$isEn ? 'meta_title_en' : 'meta_title_ar'} ?: $pageTitle;
    $metaDesc  = $page?->{$isEn ? 'meta_description_en' : 'meta_description_ar'} ?: '';
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDesc)

@section('content')

    @include('website.partials.page-hero', [
        'title' => $pageTitle,
        'subtitle' => $t('تواصل مع فريق ثقة لطلب عرض توضيحي أو استشارة فنية أو خطة تنفيذ.', 'Reach the Thiqah team for a demo, technical consultation or rollout plan.'),
    ])

    <section class="contact-section style-3 overflow-hidden space mx-30 lg-mx-0">
        <div class="bg image"><img src="{{ asset('assets/images/contact/hm6-bg01.jpg') }}" alt=""></div>
        <div class="overlay"></div>
        <div class="container">
            <div class="row align-items-center gy-40">
                <div class="col-xl-6 col-lg-6">
                    <div class="contact-content">
                        <div class="title-area white two">
                            <div class="sub-title"><span><i class="asterisk"></i></span>{{ $t('بيانات التواصل', 'Get in touch') }}</div>
                            <h2 class="sec-title">{{ $t('نحن هنا لمساعدتك على اختيار النظام المناسب', 'We are here to help you pick the right system') }}</h2>
                        </div>

                        @if($page && ($isEn ? $page->content_en : $page->content_ar))
                            <div class="thiqah-richtext mb-30" style="color:rgba(255,255,255,.85);">
                                {!! $isEn ? $page->content_en : $page->content_ar !!}
                            </div>
                        @endif

                        <ul class="list-unstyled" style="color:#fff;font-size:18px;line-height:2.2;">
                            @if($address)<li><i class="fa-solid fa-location-dot" style="color:#ff9800;margin-{{ $isEn ? 'right' : 'left' }}:12px;"></i>{{ $address }}</li>@endif
                            @if($phone)<li><i class="fa-solid fa-phone" style="color:#ff9800;margin-{{ $isEn ? 'right' : 'left' }}:12px;"></i><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" style="color:#fff;">{{ $phone }}</a></li>@endif
                            @if($whatsapp)<li><i class="fa-brands fa-whatsapp" style="color:#ff9800;margin-{{ $isEn ? 'right' : 'left' }}:12px;"></i><a href="https://wa.me/{{ preg_replace('/\D+/', '', $whatsapp) }}" style="color:#fff;" target="_blank" rel="noopener">{{ $whatsapp }}</a></li>@endif
                            @if($email)<li><i class="fa-regular fa-envelope" style="color:#ff9800;margin-{{ $isEn ? 'right' : 'left' }}:12px;"></i><a href="mailto:{{ $email }}" style="color:#fff;">{{ $email }}</a></li>@endif
                        </ul>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6">
                    <div class="appointment-area">
                        <div class="header">
                            <h2 class="title">{{ $t('أرسل لنا رسالة', 'Send us a message') }}</h2>
                            <span class="availability mb-25">{{ $t('املأ النموذج وسنعاود التواصل معك في أقرب وقت.', 'Fill the form and we will get back to you shortly.') }}</span>
                        </div>

                        @if(session('contact_success'))
                            <div style="background:#ecfdf3;border:1px solid #abefc6;color:#067647;border-radius:14px;padding:14px 18px;margin-bottom:18px;font-weight:600;">
                                {{ session('contact_success') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div style="background:#fef3f2;border:1px solid #fecdca;color:#b42318;border-radius:14px;padding:14px 18px;margin-bottom:18px;">
                                <ul style="margin:0;padding-{{ $isEn ? 'left' : 'right' }}:18px;">
                                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('website.contact.submit', ['lang' => $locale]) }}" method="post">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6 mb-15"><input class="thiqah-form-control" type="text" name="name" value="{{ old('name') }}" placeholder="{{ $t('الاسم *', 'Name *') }}" required></div>
                                <div class="col-md-6 mb-15"><input class="thiqah-form-control" type="email" name="email" value="{{ old('email') }}" placeholder="{{ $t('البريد الإلكتروني *', 'Email *') }}" required></div>
                                <div class="col-md-6 mb-15"><input class="thiqah-form-control" type="text" name="phone" value="{{ old('phone') }}" placeholder="{{ $t('رقم الجوال', 'Phone') }}"></div>
                                <div class="col-md-6 mb-15"><input class="thiqah-form-control" type="text" name="company" value="{{ old('company') }}" placeholder="{{ $t('اسم الشركة', 'Company') }}"></div>
                                <div class="col-12 mb-15"><textarea class="thiqah-form-textarea" name="message" placeholder="{{ $t('رسالتك *', 'Your message *') }}" required>{{ old('message') }}</textarea></div>
                            </div>
                            <button type="submit" class="theme-btn bg-theme mt-10"><span class="link-effect"><span class="effect-1">{{ $t('إرسال الرسالة', 'Send message') }}</span><span class="effect-1">{{ $t('إرسال الرسالة', 'Send message') }}</span></span><i class="fa-regular fa-arrow-right-long"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            @if(!empty($mapEmbed))
                <div class="row mt-50"><div class="col-12"><div class="thiqah-card" style="padding:8px;overflow:hidden;">{!! $mapEmbed !!}</div></div></div>
            @endif
        </div>
    </section>

@endsection
