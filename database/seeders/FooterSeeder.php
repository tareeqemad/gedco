<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\FooterLink;
use App\Models\SocialLink;

class FooterSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->updateOrCreate(['id' => 1], [
            'footer_title_ar' => 'تواصل معنا',
            'email'           => 'contact@logixpress.com',
            'phone'           => '+929 333 9296',
            'address_ar'      => '100 S Main St, New York, NY',
            'logo_white_path' => 'assets/site/images/logo-white.webp',
        ]);

        $services = [
            ['النقل البري','site.services'],
            ['الشحن الجوي','site.services'],
            ['الشحن البحري','site.services'],
            ['النقل بالسكك الحديدية','site.services'],
            ['المستودعات','site.services'],
            ['التخليص الجمركي','site.services'],
        ];
        foreach ($services as $i => [$label, $route]) {
            FooterLink::updateOrCreate(
                ['group'=>'services','label_ar'=>$label],
                ['route_name'=>$route,'sort_order'=>$i,'is_active'=>true]
            );
        }

        $company = [
            ['الرئيسية','site.home'],
            ['من نحن','site.about'],
            ['فريقنا','site.team'],
            ['الوظائف','site.careers'],
            ['المدونة','site.blog'],
            ['اتصل بنا','site.contact'],
        ];
        foreach ($company as $i => [$label, $route]) {
            FooterLink::updateOrCreate(
                ['group'=>'company','label_ar'=>$label],
                ['route_name'=>$route,'sort_order'=>$i,'is_active'=>true]
            );
        }

        $socials = [
            ['facebook','fa-brands fa-facebook-f','#'],
            ['x','fa-brands fa-x-twitter','#'],
            ['instagram','fa-brands fa-instagram','#'],
            ['youtube','fa-brands fa-youtube','#'],
            ['whatsapp','fa-brands fa-whatsapp','#'],
        ];
        foreach ($socials as $i => [$platform, $icon, $url]) {
            SocialLink::updateOrCreate(
                ['platform'=>$platform],
                ['icon_class'=>$icon,'url'=>$url,'sort_order'=>$i,'is_active'=>true]
            );
        }
    }
}
