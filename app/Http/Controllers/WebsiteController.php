<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebsiteController extends Controller
{
    /**
     * تحديد اللغة من البراميتر ?lang
     */
    protected function resolveLocale(Request $request): string
    {
        $locale = strtolower((string) $request->query('lang', 'ar'));
        return $locale === 'en' ? 'en' : 'ar';
    }

    /**
     * إعدادات الموقع العامة
     */
    protected function getSettings(): ?Setting
    {
        return Setting::query()->first();
    }

    /**
     * جلب قوائم الموقع (الهيدر + الفوتر) من Module القوائم (Menu/MenuItem)
     *
     * @return array{headerMenu:?Menu, footerMenu:?Menu}
     */
    protected function getMenus(): array
    {
        $headerMenu = Menu::query()
            ->where('code', 'header')
            ->where('is_active', 1)
            ->with([
                'items' => fn ($q) => $q->whereNull('parent_id')->where('is_active', 1)->orderBy('order'),
                'items.page',
                'items.children' => fn ($q) => $q->where('is_active', 1)->orderBy('order'),
                'items.children.page',
                'items.children.children' => fn ($q) => $q->where('is_active', 1)->orderBy('order'),
                'items.children.children.page',
            ])
            ->first();

        $footerMenu = Menu::query()
            ->where('code', 'footer')
            ->where('is_active', 1)
            ->with([
                'items' => fn ($q) => $q->whereNull('parent_id')->where('is_active', 1)->orderBy('order'),
                'items.page',
                'items.children' => fn ($q) => $q->where('is_active', 1)->orderBy('order'),
                'items.children.page',
            ])
            ->first();

        return [
            'headerMenu' => $headerMenu,
            'footerMenu' => $footerMenu,
        ];
    }

    /**
     * shared data لكل صفحات الموقع
     */
    protected function viewData(Request $request, array $extra = []): array
    {
        $locale = $this->resolveLocale($request);
        $settings = $this->getSettings();
        $menus = $this->getMenus();

        return array_merge([
            // ✅ خليهم بنفس أسامي اللي بتستخدمها في Blade
            'appLocale'    => $locale,
            'siteSettings' => $settings,

            // لو أنت لسه بتستخدمهم في layout/footer
            'settings'     => $settings,

            // menus
            'headerMenu'   => $menus['headerMenu'],
            'footerMenu'   => $menus['footerMenu'],
        ], $extra);
    }

    /**
     * الرئيسية
     */
    public function home(Request $request)
    {
        $services = Service::query()
            ->where('is_active', true)
            ->orderByRaw('coalesce(sort_order, 999999) asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('website.home', $this->viewData($request, [
            'services' => $services,
        ]));
    }

    /**
     * صفحة من نحن: صفحة CMS slug = about
     */
    public function about(Request $request)
    {
        $page = Page::query()
            ->where('slug', 'about')
            ->where('is_active', true)
            ->first();

        return view('website.about', $this->viewData($request, [
            'page' => $page,
        ]));
    }

    /**
     * صفحة الخدمات
     */
    public function services(Request $request)
    {
        $services = Service::query()
            ->where('is_active', true)
            ->orderByRaw('coalesce(sort_order, 999999) asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('website.services', $this->viewData($request, [
            'services' => $services,
        ]));
    }

    /**
     * تفاصيل خدمة
     */
    public function serviceDetails(Request $request, string $code)
    {
        $service = Service::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        return view('website.service-details', $this->viewData($request, [
            'service' => $service,
        ]));
    }

    /**
     * تواصل معنا: صفحة CMS slug = contact
     */
    public function contact(Request $request)
    {
        $page = Page::query()
            ->where('slug', 'contact')
            ->where('is_active', true)
            ->first();

        return view('website.contact', $this->viewData($request, [
            'page' => $page,
        ]));
    }

    /**
     * استلام فورم تواصل معنا
     */
    public function contactSubmit(Request $request)
    {
        $locale = $this->resolveLocale($request);

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:190'],
            'email'   => ['required', 'email', 'max:190'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $settings = $this->getSettings();

        $toEmail = $settings->contact_email
            ?? $settings->email
            ?? config('mail.to.address')
            ?? config('mail.from.address');

        if ($toEmail) {
            try {
                $subject = $locale === 'en'
                    ? 'New contact message from website'
                    : 'رسالة جديدة من نموذج تواصل الموقع';

                $bodyLines = [
                    'الاسم / Name: ' . $validated['name'],
                    'البريد / Email: ' . $validated['email'],
                    'الهاتف / Phone: ' . ($validated['phone'] ?? '-'),
                    'الشركة / Company: ' . ($validated['company'] ?? '-'),
                    '---------------------------',
                    $validated['message'],
                ];

                $body = nl2br(implode("\n", $bodyLines));

                Mail::send([], [], function ($message) use ($toEmail, $subject, $body) {
                    $message->to($toEmail)
                        ->subject($subject)
                        ->setBody($body, 'text/html');
                });
            } catch (\Throwable $e) {
                Log::error('Contact form mail failed', ['error' => $e->getMessage()]);
            }
        }

        $msg = $locale === 'en'
            ? 'Your message has been sent successfully. We will contact you soon.'
            : 'تم إرسال رسالتك بنجاح، وسنقوم بالتواصل معك في أقرب وقت.';

        return redirect()->back()->with('contact_success', $msg);
    }

    /**
     * صفحة ديناميكية عامة: /page/{slug}
     */
    public function page(string $slug, Request $request)
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('website.page', $this->viewData($request, [
            'page' => $page,
        ]));
    }
}
