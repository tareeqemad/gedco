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
        Schema::create('user_temporary_passwords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('password'); // مشفر باستخدام Laravel Encryption
            $table->timestamp('expires_at'); // تنتهي بعد 24 ساعة
            $table->boolean('viewed')->default(false); // هل تم عرضها؟
            $table->timestamps();
            
            $table->index(['user_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_temporary_passwords');
    }
};

