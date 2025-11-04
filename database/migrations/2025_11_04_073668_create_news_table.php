<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');

            // المحتوى
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body')->nullable();

            // ملفات
            $table->string('cover_path')->nullable();
            $table->string('pdf_path')->nullable();

            // الحالة والعرض
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('views')->default(0);

            // النشر
            $table->timestamp('published_at')->nullable();

            // تتبع المستخدمين (اختياري، مع nullOnDelete)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // الفهارس
            $table->index(['status', 'featured']);
            $table->index('published_at');

            $table->timestamps();      // created_at / updated_at
            $table->softDeletes();     // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
