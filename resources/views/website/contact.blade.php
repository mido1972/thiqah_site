{{-- resources/views/website/contact.blade.php --}}
@extends('website.layout')

@php
    // ===== Locale =====
    $locale = $locale ?? request()->query('lang', 'ar');
    $locale = strtolower($locale) === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';

    /** @var \App\Models\Page|null $page */
    $page = $page ?? null;

    // ===== Titles / SEO =====
    $pageTitleAr = $page?->title_ar ?: 'تواصل معنا';
    $pageTitleEn = $page?->title_en ?: 'Contact Us';

    $metaTitleAr = $page?->meta_title_ar ?: $pageTitleAr;
    $metaTitleEn = $page?->meta_title_en ?: $pageTitleEn;

    $metaDescAr  = $page?->meta_description_ar ?: '';
    $metaDescEn  = $page?->meta_description_en ?: '';

    // ===== Read settings safely (support $settings model OR $siteSettings array) =====
    $ss = function (string $key, $default = null) use ($settings, $siteSettings) {
        // 1) If controller provides $settings as object/model
        if (isset($settings) && is_object($settings)) {
            // common keys in your old template
            if (property_exists($settings, $key) || isset($settings->{$key})) {
                return $settings->{$key} ?? $default;
            }
        }

        // 2) New layout uses $siteSettings array
        return data_get($siteSettings ?? [], $key, $default);
    };

    $phone    = $ss('phone');
    $whatsapp = $ss('whatsapp');
    $email    = $ss('email');
    $address  = $ss('address');
    $mapEmbed = $ss('google_map_embed');

    $heroSubtitle = $isEn
        ? 'Get in touch with THIQA team for ERP, HR and contracting solutions.'
        : 'تواصل مع فريق ثقة للحصول على حلول ERP و HR وأنظمة المقاولات.';
@endphp

{{-- SEO --}}
@section('title')
    {{ $isEn ? $metaTitleEn : $metaTitleAr }}
@endsection

@section('meta_description')
    {{ $isEn ? $metaDescEn : $metaDescAr }}
@endsection

@section('content')

    {{-- Hero --}}
    @include('website.partials.page-hero', [
        'title' => $isEn ? $pageTitleEn : $pageTitleAr,
        'subtitle' => $heroSubtitle,
    ])

    <section class="section">
        <div class="container-site">

            {{-- Success / Errors --}}
            @if(session('contact_success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-900">
                    {{ session('contact_success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-900">
                    <div class="font-extrabold mb-2">{{ $isEn ? 'Please fix the following:' : 'برجاء تصحيح الآتي:' }}</div>
                    <ul class="list-disc {{ $isEn ? 'pl-6' : 'pr-6' }} space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="font-semibold">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-12">

                {{-- CMS Content --}}
                <div class="lg:col-span-7">
                    <div class="card card-pad">
                        <h2 class="h2">
                            {{ $isEn ? 'Our Message to You' : 'رسالتنا معك' }}
                        </h2>

                        <div class="mt-4 p text-{{ $isEn ? 'left' : 'right' }}">
                            @if($page)
                                @if($isEn)
                                    {!! $page->content_en ?? '' !!}
                                @else
                                    {!! $page->content_ar ?? '' !!}
                                @endif
                            @else
                                @if($isEn)
                                    <p class="p">
                                        You can reach us for demos, implementation plans and technical consultation.
                                        We will review your case and propose the best ERP package for your business.
                                    </p>
                                @else
                                    <p class="p">
                                        يمكنك التواصل معنا لطلب عرض توضيحي، أو خطة تنفيذ، أو استشارة فنية لنظام الـ ERP
                                        أو نظام الموارد البشرية المناسب لنشاط شركتك.
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact Details --}}
                <div class="lg:col-span-5">
                    <div class="card card-pad">
                        <h2 class="h2">
                            {{ $isEn ? 'Contact Details' : 'بيانات التواصل' }}
                        </h2>

                        <div class="mt-4 text-sm text-{{ $isEn ? 'left' : 'right' }}">
                            <div class="space-y-3">

                                @if(!empty($phone))
                                    <div class="flex items-start gap-2">
                                        <span class="font-extrabold text-slate-900">{{ $isEn ? 'Phone:' : 'الهاتف:' }}</span>
                                        <span class="text-slate-700">{{ $phone }}</span>
                                    </div>
                                @endif

                                @if(!empty($whatsapp))
                                    <div class="flex items-start gap-2">
                                        <span class="font-extrabold text-slate-900">WhatsApp:</span>
                                        <span class="text-slate-700">{{ $whatsapp }}</span>
                                    </div>
                                @endif

                                @if(!empty($email))
                                    <div class="flex items-start gap-2">
                                        <span class="font-extrabold text-slate-900">{{ $isEn ? 'Email:' : 'البريد الإلكتروني:' }}</span>
                                        <a class="font-extrabold text-orange-600 hover:text-orange-700"
                                           href="mailto:{{ $email }}">{{ $email }}</a>
                                    </div>
                                @endif

                                @if(!empty($address))
                                    <div class="flex items-start gap-2">
                                        <span class="font-extrabold text-slate-900">{{ $isEn ? 'Address:' : 'العنوان:' }}</span>
                                        <span class="text-slate-700">{{ $address }}</span>
                                    </div>
                                @endif

                            </div>

                            @if(!empty($mapEmbed))
                                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                                    {!! $mapEmbed !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- Contact Form --}}
            <div class="mt-6">
                <div class="card card-pad">
                    <h2 class="h2">
                        {{ $isEn ? 'Send us a message' : 'أرسل لنا رسالة' }}
                    </h2>
                    <p class="p mt-2">
                        {{ $isEn ? 'Fill the form and we will get back to you shortly.' : 'املأ النموذج وسنرد عليك في أقرب وقت.' }}
                    </p>

                    <form class="mt-6 space-y-4"
                          method="POST"
                          action="{{ route('website.contact.submit', ['lang' => $locale]) }}">
                        @csrf

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-extrabold text-slate-900 mb-2">
                                    {{ $isEn ? 'Name' : 'الاسم' }} *
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-slate-400"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-900 mb-2">
                                    {{ $isEn ? 'Email' : 'البريد الإلكتروني' }} *
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-slate-400"
                                >
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-extrabold text-slate-900 mb-2">
                                    {{ $isEn ? 'Phone' : 'الهاتف' }}
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-slate-400"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-900 mb-2">
                                    {{ $isEn ? 'Company' : 'الشركة' }}
                                </label>
                                <input
                                    type="text"
                                    name="company"
                                    value="{{ old('company') }}"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-slate-400"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-900 mb-2">
                                {{ $isEn ? 'Message' : 'الرسالة' }} *
                            </label>
                            <textarea
                                name="message"
                                rows="5"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-slate-400"
                            >{{ old('message') }}</textarea>
                        </div>

                        <div class="flex {{ $isEn ? 'justify-start' : 'justify-end' }}">
                            <button type="submit" class="btn-primary">
                                {{ $isEn ? 'Send Message' : 'إرسال الرسالة' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

@endsection
