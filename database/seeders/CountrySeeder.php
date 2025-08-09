<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $governoratesWithCities = [
            'القاهرة' => [
                'المعادي', 'المقطم', 'مدينة نصر', 'الزمالك', 'مصر الجديدة'
            ],
            'الإسكندرية' => [
                'العجمي', 'المعمورة', 'المنتزه', 'سيدي جابر', 'اللبان'
            ],
            'الجيزة' => [
                'الدقي', 'المهندسين', 'العجوزة', 'الهرم', 'الباويطي'
            ],
            'الدقهلية' => [
                'المنصورة', 'طلخا', 'ميت غمر', 'بلقاس', 'شربين'
            ],
            'البحيرة' => [
                'دمنهور', 'كفر الدوار', 'رشيد', 'إدكو', 'أبو المطامير'
            ],
            'الفيوم' => [
                'الفارسكور', 'سنورس', 'إطسا', 'طامية', 'يوسف الصديق'
            ],
            'الغربية' => [
                'طنطا', 'المحلة الكبرى', 'زفتى', 'سمنود', 'بسيون'
            ],
            'المنوفية' => [
                'شبين الكوم', 'السادات', 'منوف', 'الباجور', 'تلا'
            ],
            'أسوان' => [
                'كوم أمبو', 'دراو', 'نصر النوبة', 'كلابشة', 'إدفو'
            ],
            'الأقصر' => [
                'الزينية', 'البياضية', 'القرنة', 'أرمنت', 'إسنا'
            ]
        ];

        foreach ($governoratesWithCities as $governorate => $cities) {
            $gov = Country::create([
                'uuid' => Str::uuid(),
                'name' => $governorate,
                'parent_id' => null
            ]);

            foreach ($cities as $city) {
               // dd($gov);
                City::create([
                    'uuid' => Str::uuid(),
                    'name' => $city,
                    'parent_id' => $gov->id
                ]);
            }
        }
    }

}
