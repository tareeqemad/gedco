<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();              // العنوان الكبير
            $table->text('subtitle')->nullable();             // وصف/نص تحت العنوان
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('bg_image')->nullable();           // مسار الصورة
            $table->json('bullets')->nullable();              // العبارات الأربعة أسفل السلايد
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('sliders');
    }
};
