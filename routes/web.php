<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
|
| مسارات واجهة الموقع العامة (الزوار)
|
*/

Route::get('/', [WebsiteController::class, 'home'])
    ->name('website.home');

/**
 * صفحة من نحن (من جدول pages بسجل slug = about)
 */
Route::get('/about', [WebsiteController::class, 'about'])
    ->name('website.about');

/**
 * قائمة الأنظمة والخدمات
 */
Route::get('/services', [WebsiteController::class, 'services'])
    ->name('website.services');

/**
 * صفحة تفاصيل نظام معيّن من جدول services
 * مثال: /services/gl?lang=ar
 */
Route::get('/services/{code}', [WebsiteController::class, 'serviceDetails'])
    ->name('website.service.show');

/**
 * صفحة تواصل معنا:
 *  - GET لعرض الصفحة والمحتوى من CMS + بيانات الاتصال
 *  - POST لاستقبال نموذج التواصل وإرسال الإيميل
 */
Route::get('/contact', [WebsiteController::class, 'contact'])
    ->name('website.contact');

Route::post('/contact', [WebsiteController::class, 'contactSubmit'])
    ->name('website.contact.submit');

/**
 * صفحة ديناميكية من جدول pages
 * مثال: /page/privacy-policy
 */
Route::get('/page/{slug}', [WebsiteController::class, 'page'])
    ->name('website.page');



/*
|--------------------------------------------------------------------------
| Admin & Dashboard Routes (protected by auth)
|--------------------------------------------------------------------------
|
| مسارات لوحة التحكم الداخلية، محمية بوسيط المصادقة auth
|
*/

Route::middleware('auth')->group(function () {

    // بعد تسجيل الدخول، Laravel يحوّل عادة إلى /dashboard
    // هنا نعيد توجيهه إلى لوحة تحكم الأدمن الأساسية
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // مسارات لوحة التحكم تحت /admin/...
    Route::prefix('admin')
        ->as('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');
        });
});
