@php
    /** @var \Illuminate\Support\Collection|\App\Models\Service[] $services */
    $locale = $locale ?? request()->query('lang', 'ar');
    $locale = strtolower((string) $locale) === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';

    $pageTitle = $isEn ? 'Services' : 'الخدمات';
    $pageDescription = $isEn
        ? 'Explore Thiqah I-Tech ERP, HR, e-invoicing and contracting modules.'
        : 'استكشف أنظمة ERP و HR والفاتورة الإلكترونية وأنظمة المقاولات.';
@endphp

@extends('website.layout')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('content')

@include('website.partials.page-hero', [
    'title' => $isEn ? 'Modules & Services' : 'الأنظمة والخدمات',
    'subtitle' => $isEn
        ? 'A complete suite covering finance, inventory, sales, purchasing, e-invoicing and HR & payroll.'
        : 'باقة متكاملة تغطي الحسابات والمخازن والمبيعات والمشتريات والفاتورة الإلكترونية والموارد البشرية والرواتب.'
])

<section class="section">
    <div class="container-site">

        <div class="text-center mb-10">
            <h2 class="h2">{{ $isEn ? 'Available Modules' : 'الأنظمة المتاحة' }}</h2>
            <p class="p mt-3">
                {{ $isEn
                    ? 'Choose what fits your business, then request a proposal and implementation plan.'
                    : 'اختر الأنظمة المناسبة لنشاطك، ثم اطلب عرضًا وخطة تنفيذ.' }}
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($services as $service)
                @php
                    // ✅ FIX: titles are title_ar / title_en
                    $title = $isEn
                        ? ($service->title_en ?? $service->title_ar ?? $service->code ?? '')
                        : ($service->title_ar ?? $service->title_en ?? $service->code ?? '');

                    $title = trim((string) $title);

                    $desc  = $isEn ? ($service->description_en ?? '') : ($service->description_ar ?? '');
                    $desc  = \Illuminate\Support\Str::limit(trim(strip_tags((string) $desc)), 160);

                    $detailsUrl = route('website.service.show', ['code' => $service->code, 'lang' => $locale]);
                    $badge = strtoupper(substr((string) ($service->code ?? 'S'), 0, 1));
                @endphp

                <a href="{{ $detailsUrl }}" class="card card-pad hover:border-orange-300 transition block">
                    <div class="flex items-start gap-3">
                        <div class="h-11 w-11 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center font-black">
                            {{ $badge }}
                        </div>
                        <div class="min-w-0">
                            <div class="text-base font-extrabold text-slate-900">
                                {{ $title ?: '—' }}
                            </div>

                            <p class="p mt-2">{{ $desc }}</p>

                            <span class="btn-link inline-flex mt-3">
                                {{ $isEn ? 'View details' : 'عرض التفاصيل' }}
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="card card-pad md:col-span-2 lg:col-span-3 text-center">
                    <h3 class="h3">{{ $isEn ? 'No services yet' : 'لا توجد خدمات بعد' }}</h3>
                    <p class="p mt-2">{{ $isEn ? 'Add services from Admin panel.' : 'أضف الخدمات من لوحة التحكم.' }}</p>
                </div>
            @endforelse
        </div>

    </div>
</section>

@endsection
