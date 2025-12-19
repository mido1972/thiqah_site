<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            // القائمة الأب
            $table->foreignId('menu_id')
                ->constrained('menus')
                ->cascadeOnDelete();

            // لو عنصر من قائمة منسدلة: parent_id = id لعنصر تاني في نفس الجدول
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menu_items')
                ->nullOnDelete();

            // عناوين العنصر
            $table->string('title_ar');
            $table->string('title_en')->nullable();

            // نوع الرابط: صفحة / route / url خارجي
            $table->string('type', 20)->default('page');

            // ربط بجدول الصفحات (اختياري)
            $table->foreignId('page_id')
                ->nullable()
                ->constrained('pages')
                ->nullOnDelete();

            // لو type = route
            $table->string('route_name')->nullable();

            // لو type = url
            $table->string('url')->nullable();

            // فتح في تبويب جديد؟
            $table->boolean('open_in_new_tab')->default(false);

            $table->unsignedInteger('order')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
