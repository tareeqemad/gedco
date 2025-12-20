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
        // إضافة footer_title_en في site_settings
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('footer_title_en')->nullable()->after('footer_title_ar');
        });

        // إضافة address_en في site_contact_channels
        Schema::table('site_contact_channels', function (Blueprint $table) {
            $table->string('address_en')->nullable()->after('address_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('footer_title_en');
        });

        Schema::table('site_contact_channels', function (Blueprint $table) {
            $table->dropColumn('address_en');
        });
    }
};
