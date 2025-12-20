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
        Schema::table('about_us', function (Blueprint $table) {
            // إضافة الأعمدة الإنجليزية
            $table->string('title_en', 255)->nullable()->after('title');
            $table->string('subtitle_en', 255)->nullable()->after('subtitle');
            $table->text('paragraph1_en')->nullable()->after('paragraph1');
            $table->text('paragraph2_en')->nullable()->after('paragraph2');
        });

        // ترحيل البيانات من السجل الإنجليزي إلى السجل العربي
        $arabicRecord = DB::table('about_us')->where('language', 'ar')->first();
        $englishRecord = DB::table('about_us')->where('language', 'en')->first();

        if ($arabicRecord && $englishRecord) {
            // تحديث السجل العربي بالبيانات الإنجليزية
            DB::table('about_us')
                ->where('id', $arabicRecord->id)
                ->update([
                    'title_en' => $englishRecord->title,
                    'subtitle_en' => $englishRecord->subtitle,
                    'paragraph1_en' => $englishRecord->paragraph1,
                    'paragraph2_en' => $englishRecord->paragraph2,
                ]);

            // حذف السجل الإنجليزي
            DB::table('about_us')->where('id', $englishRecord->id)->delete();
        } elseif ($arabicRecord) {
            // إذا كان هناك سجل عربي فقط، نترك الحقول الإنجليزية null
        } elseif ($englishRecord) {
            // إذا كان هناك سجل إنجليزي فقط، نغير language إلى ar ونترك الحقول العربية null
            DB::table('about_us')
                ->where('id', $englishRecord->id)
                ->update([
                    'language' => 'ar',
                    'title' => null,
                    'subtitle' => null,
                    'paragraph1' => null,
                    'paragraph2' => null,
                    'title_en' => $englishRecord->title,
                    'subtitle_en' => $englishRecord->subtitle,
                    'paragraph1_en' => $englishRecord->paragraph1,
                    'paragraph2_en' => $englishRecord->paragraph2,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'subtitle_en', 'paragraph1_en', 'paragraph2_en']);
        });
    }
};
