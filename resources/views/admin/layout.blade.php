@php
    $appLocale = 'ar'; // لوحة التحكم بالعربي مبدئياً
    $companyNameAr = $settings->company_name ?? 'ثقة لتقنية نظم المعلومات';
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>لوحة التحكم - {{ $companyNameAr }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 من CDN للوحة التحكم فقط --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        body {
            font-family: "Cairo", -apple-system, BlinkMacSystemFont,
                         "Segoe UI", "Helvetica Neue", Arial, sans-serif;
            background-color: #f3f4f6;
        }

        .admin-navbar {
            background-color: #020617;
        }

        .admin-navbar .navbar-brand,
        .admin-navbar .nav-link,
        .admin-navbar .navbar-text {
            color: #f9fafb !important;
        }

        .admin-navbar .nav-link.active {
            color: #f97316 !important;
            font-weight: 700;
        }

        .admin-content {
            padding-top: 80px;
            padding-bottom: 30px;
        }

        .card-stat {
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        }

        .card-stat h3 {
            font-size: 2rem;
            margin-bottom: 0.25rem;
        }

        .badge-soft {
            background-color: rgba(249, 115, 22, .08);
            color: #f97316;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.75rem;
        }
    </style>

    @stack('admin_styles')
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top admin-navbar shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            {{ $companyNameAr }}
            <span class="badge-soft ms-2">لوحة التحكم</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#adminNavbar" aria-controls="adminNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        الرئيسية
                    </a>
                </li>
                {{-- هنضيف روابط إدارة الخدمات والمحتوى هنا لاحقاً --}}
            </ul>

            <div class="d-flex align-items-center gap-3">
                <span class="navbar-text small">
                    {{ auth()->user()->name ?? 'Admin' }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light" type="submit">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="container admin-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('admin_scripts')
</body>
</html>
