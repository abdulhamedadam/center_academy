<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Courses;
use App\Models\Instructor;
use App\Models\Levels;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'تعلم البرمجة للمبتدئين',
                'description' => 'دورة شاملة لتعليم أساسيات البرمجة للمبتدئين',
                'price' => 299,
                'discount_type' => 'p',
                'discount' => 10,
                'duration' => 3,
            ],
//            [
//                'name' => 'التصميم الجرافيكي باستخدام الفوتوشوب',
//                'description' => 'احترف تصميم الجرافيك باستخدام أدوات الفوتوشوب',
//                'price' => 399,
//                'discount_type' => 'v',
//                'discount' => 50,
//                'duration' => 2.5,
//            ],
//            [
//                'name' => 'تعلم اللغة الإنجليزية من الصفر',
//                'description' => 'دورة متكاملة لتعلم الإنجليزية للمبتدئين',
//                'price' => 199,
//                'discount_type' => null,
//                'discount' => 0,
//                'duration' => 4,
//            ],
//            [
//                'name' => 'التسويق الإلكتروني المتقدم',
//                'description' => 'استراتيجيات التسويق الرقمي لزيادة المبيعات',
//                'price' => 499,
//                'discount_type' => 'p',
//                'discount' => 15,
//                'duration' => 3,
//            ],
//            [
//                'name' => 'برمجة تطبيقات الأندرويد',
//                'description' => 'تعلم بناء تطبيقات الأندرويد من الصفر للإحتراف',
//                'price' => 599,
//                'discount_type' => 'v',
//                'discount' => 100,
//                'duration' => 5,
//            ],
//            [
//                'name' => 'تحليل البيانات باستخدام Excel',
//                'description' => 'إتقان تحليل البيانات وإعداد التقارير',
//                'price' => 249,
//                'discount_type' => 'p',
//                'discount' => 20,
//                'duration' => 2,
//            ],
//            [
//                'name' => 'التصوير الفوتوغرافي الاحترافي',
//                'description' => 'أساسيات التصوير الفوتوغرافي ومعالجة الصور',
//                'price' => 349,
//                'discount_type' => null,
//                'discount' => 0,
//                'duration' => 3,
//            ],
//            [
//                'name' => 'تعلم الذكاء الاصطناعي',
//                'description' => 'مدخل إلى عالم الذكاء الاصطناعي وتعلم الآلة',
//                'price' => 699,
//                'discount_type' => 'p',
//                'discount' => 25,
//                'duration' => 6,
//            ],
//            [
//                'name' => 'إدارة المشاريع الاحترافية',
//                'description' => 'تعلم أدوات ومهارات إدارة المشاريع باحترافية',
//                'price' => 449,
//                'discount_type' => 'v',
//                'discount' => 75,
//                'duration' => 3.5,
//            ],
//            [
//                'name' => 'البرمجة بلغة Python',
//                'description' => 'تعلم البرمجة بلغة Python من البداية',
//                'price' => 399,
//                'discount_type' => 'p',
//                'discount' => 10,
//                'duration' => 4,
//            ],
        ];

        foreach ($courses as $courseData) {
            $startDate = Carbon::now()->addDays(rand(1, 30));

            $course = Courses::create([
                'category_id' => Category::inRandomOrder()->first()->id,
                'level_id' => Levels::inRandomOrder()->first()->id,
                'instructor_id' => Instructor::inRandomOrder()->first()->id,
                'name' => $courseData['name'],
                'description' => $courseData['description'],
                'price' => $courseData['price'],
                'discount_type' => $courseData['discount_type'],
                'discount' => $courseData['discount'],
                'duration' => $courseData['duration'],
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addMonths($courseData['duration']),
                'max_students' => rand(20, 50),
                'status' => true,
            ]);

            // Calculate total price
            $total = match ($courseData['discount_type']) {
                'p' => $courseData['price'] - ($courseData['price'] * $courseData['discount'] / 100),
                'v' => max($courseData['price'] - $courseData['discount'], 0),
                default => $courseData['price'],
            };

            $course->update(['total_price' => $total]);
        }
    }
}
