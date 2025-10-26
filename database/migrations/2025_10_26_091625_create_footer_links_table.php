<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('footer_links', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index(); // services | company
            $table->string('label_ar');
            $table->string('route_name')->nullable(); // اسم route لو داخلي
            $table->string('url')->nullable();        // رابط خارجي بديل
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('footer_links');
    }
};
