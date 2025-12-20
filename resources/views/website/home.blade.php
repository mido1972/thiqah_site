@php
    /** @var \Illuminate\Support\Collection|\App\Models\Service[] $services */
    $locale = request()->query('lang','ar') === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';

    // ✅ safe slides count
    $activeSlidesCount = ($heroSlider?->slides?->where('is_active', true)->count() ?? 0);
@endphp

@extends('website.layout')

@section('title', $isEn ? 'Thiqah I-Tech' : 'ثقة لتقنية نظم المعلومات')

@section('content')

{{-- ✅ Hero Slider (DB Driven) --}}
@include('website.partials.hero-slider', [
    'heroSlider' => $heroSlider ?? null,
    'locale' => $locale,
])

{{-- ✅ Fallback only if slider missing OR has no active slides --}}
@if(empty($heroSlider) || $activeSlidesCount === 0)
    @include('website.partials.page-hero', [
        'title' => $isEn ? 'ERP, HR & Contracting Systems' : 'حلول ERP و HR وأنظمة المقاولات',
        'subtitle' => $isEn
            ? 'Enterprise-grade solutions with clean UI, secure workflows, and scalable architecture.'
            : 'حلول احترافية بواجهة نظيفة وسير عمل آمن وقابل للتوسع.',
        'ctaText' => $isEn ? 'Explore Services' : 'استعراض الخدمات',
        'ctaUrl'  => url('/services') . '?lang=' . $locale,
    ])
@endif

<section class="section">
    <div class="container-site">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="card card-pad">
                <h2 class="h3">{{ $isEn ? 'Fast Implementation' : 'تنفيذ سريع' }}</h2>
                <p class="p mt-2">{{ $isEn ? 'Clear plan, milestones, and delivery.' : 'خطة واضحة ومراحل تنفيذ وتسليم.' }}</p>
            </div>
            <div class="card card-pad">
                <h2 class="h3">{{ $isEn ? 'Secure Workflows' : 'سير عمل آمن' }}</h2>
                <p class="p mt-2">{{ $isEn ? 'Roles, approvals, and audit trail.' : 'صلاحيات واعتمادات وسجل قرارات.' }}</p>
            </div>
            <div class="card card-pad">
                <h2 class="h3">{{ $isEn ? 'Scalable Modules' : 'موديولات قابلة للتوسع' }}</h2>
                <p class="p mt-2">{{ $isEn ? 'Add modules as your business grows.' : 'توسع بإضافة أنظمة حسب نمو شركتك.' }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-site">

        <div class="flex items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="h2">{{ $isEn ? 'Featured Services' : 'خدمات مختارة' }}</h2>
                <p class="p mt-2">
                    {{ $isEn ? 'A quick look at the most requested modules.' : 'نظرة سريعة على أهم الأنظمة المطلوبة.' }}
                </p>
            </div>

            <a href="{{ url('/services') }}?lang={{ $locale }}" class="btn-secondary">
                {{ $isEn ? 'All Services' : 'كل الخدمات' }}
            </a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse(($services ?? collect())->take(6) as $service)
                @php
                    $title = $isEn ? ($service->name_en ?? $service->name_ar ?? '') : ($service->name_ar ?? $service->name_en ?? '');
                    $desc  = $isEn ? ($service->description_en ?? '') : ($service->description_ar ?? '');
                    $desc  = \Illuminate\Support\Str::limit(trim(strip_tags($desc)), 140);

                    $detailsUrl = route('website.service.show', ['code' => $service->code, 'lang' => $locale]);
                    $badge = strtoupper(substr($service->code ?? 'S', 0, 1));
                @endphp

                <a href="{{ $detailsUrl }}" class="card card-pad hover:border-orange-300 transition block">
                    <div class="flex items-start gap-3">
                        <div class="h-11 w-11 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center font-black">
                            {{ $badge }}
                        </div>

                        <div class="min-w-0">
                            <div class="text-base font-extrabold text-slate-900">{{ $title }}</div>
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
