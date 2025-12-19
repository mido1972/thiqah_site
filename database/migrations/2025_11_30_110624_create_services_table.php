<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // مفتاح داخلي لو حابب تستخدمه في الكود
            $table->string('code')->unique();

            // العناوين
            $table->string('title_ar');
            $table->string('title_en');

            // الوصف
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();

            // أيقونة Font Awesome مثلاً: fa-calculator, fa-archive ...
            $table->string('icon_class')->nullable();

            // ترتيب العرض في الصفحة
            $table->unsignedInteger('sort_order')->default(0);

            // تفعيل / إخفاء
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
