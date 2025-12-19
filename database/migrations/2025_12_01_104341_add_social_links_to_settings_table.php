<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // نفترض أن أعمدة الجدول موجودة مسبقًا كما في الـ Model
            // نضيف بعد linkedin لضمان ترتيب منظم
            $table->string('twitter')->nullable()->after('linkedin');
            $table->string('instagram')->nullable()->after('twitter');
            $table->string('youtube')->nullable()->after('instagram');
            $table->string('tiktok')->nullable()->after('youtube');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['twitter', 'instagram', 'youtube', 'tiktok']);
        });
    }
};
