<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_id')->constrained('hero_sliders')->cascadeOnDelete();

            $table->string('title_ar', 255);
            $table->string('title_en', 255)->nullable();

            $table->string('subtitle_ar', 255)->nullable();
            $table->string('subtitle_en', 255)->nullable();

            $table->longText('content_ar')->nullable();
            $table->longText('content_en')->nullable();

            $table->string('cta_label_ar', 100)->nullable();
            $table->string('cta_label_en', 100)->nullable();
            $table->string('cta_url', 255)->nullable();

            $table->string('main_image', 255)->nullable();

            // JSON array of image paths (e.g. ["hero/overlays/1.png","hero/overlays/2.png"])
            $table->json('overlay_images')->nullable();

            $table->unsignedInteger('order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();

            $table->index(['slider_id', 'is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
