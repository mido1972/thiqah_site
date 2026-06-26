@php
    /** @var \App\Models\Page $page */
    $appLocale = ($locale ?? request()->query('lang', 'ar')) === 'en' ? 'en' : 'ar';
    $isEn = $appLocale === 'en';

    $pageTitle = $page->getMetaTitle($appLocale);
    $pageDescription = $page->getMetaDescription($appLocale);
    $content = $page->getContent($appLocale);
@endphp

@extends('website.layout')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('content')

    @include('website.partials.page-hero', [
        'title' => $page->getTitle($appLocale),
        'subtitle' => $pageDescription ?: null,
    ])

    <section class="space bg-theme3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="thiqah-card">
                        <div class="thiqah-richtext">
                            @if($content)
                                {!! $content !!}
                            @else
                                <p>{{ $isEn ? 'Content for this page will be added soon from the admin panel.' : 'سيُضاف محتوى هذه الصفحة قريبًا من لوحة التحكم.' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
