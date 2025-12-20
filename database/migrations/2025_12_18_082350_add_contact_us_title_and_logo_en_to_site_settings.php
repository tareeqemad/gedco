<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // إضافة الحقول الجديدة
            $table->string('logo_white_path_ar')->nullable()->after('footer_title_en');
            $table->string('logo_white_path_en')->nullable()->after('logo_white_path_ar');
            $table->string('contact_us_title_ar')->nullable()->after('logo_white_path_en');
            $table->string('contact_us_title_en')->nullable()->after('contact_us_title_ar');
        });

        // نقل البيانات من logo_white_path إلى logo_white_path_ar
        DB::table('site_settings')->update([
            'logo_white_path_ar' => DB::raw('logo_white_path')
        ]);

        // إزالة الحقل القديم (بعد التأكد من نقل البيانات)
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('logo_white_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // إعادة إضافة الحقل القديم
            $table->string('logo_white_path')->nullable()->after('logo_white_path_en');
        });

        // نقل البيانات مرة أخرى
        DB::table('site_settings')->update([
            'logo_white_path' => DB::raw('logo_white_path_ar')
        ]);

        // إزالة الحقول الجديدة
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['contact_us_title_ar', 'contact_us_title_en', 'logo_white_path_en', 'logo_white_path_ar']);
        });
    }
};
