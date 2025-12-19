<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية.
     */
    public function index()
    {
        $settings = Setting::first();

        $stats = [
            'users_count'    => User::count(),
            'services_count' => class_exists(Service::class) ? Service::count() : 0,
        ];

        return view('admin.dashboard', compact('settings', 'stats'));
    }
}
