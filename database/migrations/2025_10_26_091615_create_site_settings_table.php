<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('footer_title_ar')->default('تواصل معنا');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_ar')->nullable();
            // خليه يشير لمسار داخل public/assets/site/...
            $table->string('logo_white_path')->nullable()->default('assets/site/images/logo-white.webp');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('site_settings');
    }
};

