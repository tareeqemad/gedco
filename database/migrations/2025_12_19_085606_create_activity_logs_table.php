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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action'); // 'view', 'create', 'update', 'delete', 'login', 'logout', etc.
            $table->string('model_type')->nullable(); // مثل: App\Models\News, App\Models\Slider
            $table->unsignedBigInteger('model_id')->nullable(); // ID للموديل
            $table->string('route_name')->nullable(); // اسم الراوت
            $table->string('route_uri'); // URI
            $table->string('method'); // GET, POST, PUT, DELETE, etc.
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->json('request_data')->nullable(); // البيانات المرسلة (POST/PUT)
            $table->json('old_values')->nullable(); // القيم القديمة عند التعديل
            $table->json('new_values')->nullable(); // القيم الجديدة عند التعديل
            $table->string('description')->nullable(); // وصف مختصر
            $table->timestamps(); // created_at و updated_at

            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index('route_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};