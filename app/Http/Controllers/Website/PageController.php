<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * الصفحة الرئيسية
     */
    public function home()
    {
        // ممكن لاحقًا نجيب بيانات من قاعدة البيانات للهوم
        return view('website.home');
    }

    /**
     * عرض صفحة ديناميكية من جدول pages عن طريق الـ slug
     */
    public function show(string $slug)
    {
        // نجيب الصفحة أو نطلع 404 لو مش موجودة
        $page = Page::where('slug', $slug)->firstOrFail();

        return view('website.page', compact('page'));
    }
}
