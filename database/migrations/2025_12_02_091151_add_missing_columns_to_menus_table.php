<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // نضيف العمود code لو مش موجود
            if (! Schema::hasColumn('menus', 'code')) {
                $table->string('code', 50)
                    ->nullable()
                    ->after('location');
            }

            // لو حابب تتأكد إن الأعمدة دي موجودة برضه
            if (! Schema::hasColumn('menus', 'order')) {
                $table->unsignedInteger('order')
                    ->default(0)
                    ->after('code');
            }

            if (! Schema::hasColumn('menus', 'is_active')) {
                $table->boolean('is_active')
                    ->default(true)
                    ->after('order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // غالبًا مش هنرجع الـ down، ولو رجعته محتاج doctrine/dbal عشان dropColumn بشرط
            // هنسيبها فاضية لتفادي مشاكل إضافية
        });
    }
};
