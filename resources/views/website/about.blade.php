@php
    $locale = $locale ?? request()->query('lang', 'ar');
    $locale = strtolower($locale) === 'en' ? 'en' : 'ar';
    $isEn = $locale === 'en';

    /** @var \App\Models\Page|null $page */
    $page = $page ?? null;

    $title = $isEn ? ($page?->title_en ?? 'About Us') : ($page?->title_ar ?? 'من نحن');
    $desc  = $isEn
        ? ($page?->meta_description_en ?? 'Learn about Thiqah I-Tech and our enterprise solutions.')
        : ($page?->meta_description_ar ?? 'تعرف على ثقة لتقنية نظم المعلومات وحلولنا المؤسسية.');
@endphp

@extends('website.layout')

@section('title', $title)
@section('meta_description', $desc)

@section('content')

@include('website.partials.page-hero', [
    'title' => $title,
    'subtitle' => $isEn
        ? 'We build ERP, HR and contracting platforms with modern UI and secure workflows.'
        : 'نطوّر منصات ERP و HR وأنظمة المقاولات بواجهة حديثة وسير عمل آمن.'
])

<section class="section">
    <div class="container-site">
        <div class="card card-pad">
            <h2 class="h2">{{ $isEn ? 'Who we are' : 'من نحن' }}</h2>

            <div class="mt-4 p text-{{ $isEn ? 'left' : 'right' }}">
                @if($page)
                    {!! $isEn ? ($page->content_en ?? '') : ($page->content_ar ?? '') !!}
                @else
                    <p class="p">
                        {{ $isEn
                            ? 'Add the About page content from Admin → Pages.'
                            : 'أضف محتوى صفحة من نحن من لوحة التحكم → الصفحات.' }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
