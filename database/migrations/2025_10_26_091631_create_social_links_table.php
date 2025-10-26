<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform');    // facebook / x / instagram / youtube / whatsapp
            $table->string('icon_class');  // fa-brands fa-facebook-f ...
            $table->string('url');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('social_links');
    }
};
