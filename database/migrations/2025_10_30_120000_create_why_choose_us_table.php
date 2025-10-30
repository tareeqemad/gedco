<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('why_choose_us', function (Blueprint $table) {
            $table->id();
            $table->string('badge')->default('لماذا تختارنا');   // النص داخل الشارة الصغيرة
            $table->string('tagline');                            // شريكك الموثوق...
            $table->text('description')->nullable();              // الفقرة التعريفية
            $table->json('features')->nullable();                 // [{title,text,icon}, ...]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('why_choose_us');
    }
};
