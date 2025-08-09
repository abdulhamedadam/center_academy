<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'البرمجة وتطوير الويب',
                'description' => 'كورسات برمجة وتطوير مواقع وتطبيقات',

            ],
            [
                'name' => 'التصميم الجرافيكي',
                'description' => 'تعلم التصميم بالفوتوشوب والرسوم الرقمية',

            ],
            [
                'name' => 'اللغات الأجنبية',
                'description' => 'دورات لتعلم الإنجليزية والفرنسية وغيرها',

            ],
            [
                'name' => 'التسويق الرقمي',
                'description' => 'تعلم التسويق الإلكتروني والسوشيال ميديا',

            ],
            [
                'name' => 'العلوم والهندسة',
                'description' => 'مواد علمية وهندسية متخصصة',

            ],
            [
                'name' => 'التصوير الفوتوغرافي',
                'description' => 'فنون التصوير ومعالجة الصور',

            ],
            [
                'name' => 'إدارة الأعمال',
                'description' => 'دورات في الإدارة والقيادة وريادة الأعمال',

            ],
            [
                'name' => 'الذكاء الاصطناعي',
                'description' => 'تعلم الآلة والذكاء الاصطناعي المتقدم',

            ],
            [
                'name' => 'التنمية البشرية',
                'description' => 'تنمية المهارات الشخصية والقيادية',

            ],
            [
                'name' => 'الطب والصحة',
                'description' => 'مواد طبية وصحية للمتخصصين',

            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
            ]);
        }
    }
}
