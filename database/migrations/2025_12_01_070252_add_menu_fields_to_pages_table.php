<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة أعمدة خاصة بالهيدر/الفوتر وحالة التفعيل إلى جدول الصفحات
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // هل الصفحة مفعّلة وتظهر في الواجهة؟
            $table->boolean('is_active')
                ->default(true)
                ->after('meta_description_en');

            // هل تظهر في الهيدر؟
            $table->boolean('show_in_header')
                ->default(false)
                ->after('is_active');

            // هل تظهر في الفوتر (روابط سريعة)؟
            $table->boolean('show_in_footer')
                ->default(false)
                ->after('show_in_header');

            // ترتيب العرض في القوائم
            $table->unsignedInteger('sort_order')
                ->default(0)
                ->after('show_in_footer');
        });
    }

    /**
     * التراجع عن التعديلات في حالة rollback
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'show_in_header',
                'show_in_footer',
                'sort_order',
            ]);
        });
    }
};
