@php
    /** @var \App\Models\Page $page */

    $appLocale = $locale ?? 'ar';

    $pageTitle = $page->getMetaTitle($appLocale);
    $pageDescription = $page->getMetaDescription($appLocale);

    // محتوى حسب اللغة
    $content = $page->getContent($appLocale);
@endphp

@extends('website.layout')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('extra_head')
    <style>
        .page-hero {
            background: radial-gradient(circle at top left, #020617 0%, #020617 55%, #020617 100%);
            color: #fff;
            padding: 80px 0 45px;
        }
        .page-hero-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 6px;
        }
        .page-hero-breadcrumb {
            font-size: 0.8rem;
            margin-bottom: 6px;
            color: #9ca3af;
        }
        .page-hero-breadcrumb a {
            color: #cbd5f5;
        }
        .page-hero-breadcrumb a:hover {
            color: #f97316;
        }

        .page-body {
            padding: 45px 0 70px;
            background-color: #f9fafb;
        }
        .page-panel {
            background: #ffffff;
            border-radius: 18px;
            padding: 26px 26px 30px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid #e5e7eb;
        }
        .page-panel h2, .page-panel h3, .page-panel h4 {
            color: #111827;
        }
        .page-panel p {
            font-size: 0.96rem;
            line-height: 1.9;
            color: #374151;
        }
        .page-panel ul, .page-panel ol {
            padding-{{ $appLocale === 'en' ? 'left' : 'right' }}: 22px;
        }

        @media (max-width: 767px) {
            .page-hero {
                padding-top: 70px;
            }
            .page-hero-title {
                font-size: 1.7rem;
            }
        }
    </style>
@endsection

@section('content')

    {{-- HERO --}}
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-breadcrumb">
                <a href="{{ route('website.home', ['lang' => $appLocale]) }}">
                    {{ $appLocale === 'en' ? 'Home' : 'الرئيسية' }}
                </a>
                /
                <span>{{ $page->getTitle($appLocale) }}</span>
            </div>

            <h1 class="page-hero-title">
                {{ $page->getTitle($appLocale) }}
            </h1>
            @if($pageDescription)
                <p style="max-width:650px; font-size:0.95rem; line-height:1.7;">
                    {{ $pageDescription }}
                </p>
            @endif
        </div>
    </section>

    {{-- BODY --}}
    <section class="page-body">
        <div class="container">
            <div class="page-panel">
                @if($content)
                    {{-- RichEditor يخرج HTML، لذلك نستخدم {!! !!} --}}
                    {!! $content !!}
                @else
                    <p>
                        {{ $appLocale === 'en'
                            ? 'Content for this page will be added soon from the admin panel.'
                            : 'سيتم إضافة محتوى هذه الصفحة قريبًا من خلال لوحة التحكم.' }}
                    </p>
                @endif
            </div>
        </div>
    </section>

@endsection
