@extends('admin.layout')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-1">لوحة التحكم</h1>
            <p class="text-muted mb-0">
                مؤشر سريع لحالة النظام والمحتوى المرتبط بالموقع.
            </p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-stat border-0">
                <div class="card-body">
                    <span class="badge-soft mb-2">المستخدمون</span>
                    <h3>{{ $stats['users_count'] }}</h3>
                    <p class="text-muted mb-0">عدد مستخدمي النظام المسجلين.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-stat border-0">
                <div class="card-body">
                    <span class="badge-soft mb-2">الخدمات / الأنظمة</span>
                    <h3>{{ $stats['services_count'] }}</h3>
                    <p class="text-muted mb-0">
                        عدد الأنظمة أو الخدمات المعرفة في قاعدة البيانات
                        (سنربطها بواجهة الموقع في الخطوة التالية).
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-stat border-0">
                <div class="card-body">
                    <span class="badge-soft mb-2">إعدادات الشركة</span>
                    <h3>{{ $settings ? '✓' : '✕' }}</h3>
                    <p class="text-muted mb-0">
                        {{ $settings
                            ? 'تم حفظ بيانات الشركة الأساسية في جدول settings.'
                            : 'لم يتم إدخال بيانات الشركة بعد (جدول settings فارغ).' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- مكان لإضافة مخطط أو روابط سريعة لاحقاً --}}
@endsection
