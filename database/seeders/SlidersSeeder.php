<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlidersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sliders')->truncate(); // عشان نبدأ من الصفر بدون تكرار

        $commonBullets = json_encode([
            'جهودنا لا تنطفئ',
            'قوتنا في التزامنا',
            'معًا نعيد النور',
            'نضيء طريق الحياة',
        ], JSON_UNESCAPED_UNICODE);

        DB::table('sliders')->insert([
            [
                'title'       => 'معًا نعيد النور… ونواصل الخدمة رغم كل الصعاب.',
                'subtitle'    => 'نقف صفًا واحدًا لإعادة التيار في كل لحظة انقطاع، لأن رسالتنا أعمق من الكهرباء… إنها خدمة الناس واستمرار الحياة.',
                'bg_image'    => 'assets/site/images/sliders/s1.webp',
                'sort_order'  => 0,
                'bullets'     => $commonBullets,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'من قلب التحدي… نُشعل الأمل.',
                'subtitle'    => 'وسط الدمار والظروف الصعبة، يخرج عمال الكهرباء ليعيدوا الحياة بخيوط الضوء، حاملين الأمل لكل بيت وشارع.',
                'bg_image'    => 'assets/site/images/sliders/s2.webp',
                'sort_order'  => 1,
                'bullets'     => $commonBullets,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'الكهرباء ليست مجرد طاقة… إنها وعد بالاستمرار.',
                'subtitle'    => 'نلتزم بأن تبقى الكهرباء رمزًا للاستقرار، ونعمل كل يوم على الوفاء بوعدنا لتستمر الحياة بلا انقطاع.',
                'bg_image'    => 'assets/site/images/sliders/s3.webp',
                'sort_order'  => 2,
                'bullets'     => $commonBullets,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
