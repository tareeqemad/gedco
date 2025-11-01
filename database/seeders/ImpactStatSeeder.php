<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImpactStat;

class ImpactStatSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['title_ar' => 'خسائر المباني',         'amount_usd' => 7_000_000.0,     'sort_order' => 1],
            ['title_ar' => 'خسائر القطاع التجاري',  'amount_usd' => 196_900_000.0,   'sort_order' => 2],
            ['title_ar' => 'خسائر الشبكات',         'amount_usd' => 204_000_000.0,   'sort_order' => 3],
            ['title_ar' => 'خسائر المستودعات',      'amount_usd' => 20_000_000.0,    'sort_order' => 4],
            ['title_ar' => 'خسائر المركبات والآليات','amount_usd' => 5_500_000.0,    'sort_order' => 5],
            ['title_ar' => 'الخسائر التشغيلية',     'amount_usd' => 15_000_000.0,    'sort_order' => 6],
        ];

        foreach ($rows as $r) {
            ImpactStat::updateOrCreate(
                ['title_ar' => $r['title_ar']],
                $r + ['is_active' => true]
            );
        }
    }
}
