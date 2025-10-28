<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');             // مثال: IATA (الاتحاد الدولي للنقل الجوي)
            $table->string('slug')->unique();    // اختياري لسيو/روابط
            $table->string('image')->nullable(); // path للصورة
            $table->string('link')->nullable();  // للرابط اللي بينفتح في الـ popup (لو حابب)
            $table->text('description')->nullable(); // الوصف تحت العنوان
            $table->unsignedInteger('sort')->default(0); // للترتيب
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('jobs');
    }
};
