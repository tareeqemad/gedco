<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->comment('العنوان الرئيسي');
            $table->string('subtitle', 255)->nullable()->comment('العبارة التعريفية');
            $table->text('paragraph1')->comment('الفقرة الأولى');
            $table->text('paragraph2')->nullable()->comment('الفقرة الثانية');
            $table->json('features')->nullable()->comment('قوائم الخصائص بصيغة JSON');
            $table->string('image')->nullable()->comment('مسار الصورة في التخزين');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
