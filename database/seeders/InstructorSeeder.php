<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Instructor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instructor::query()->delete();
        Storage::makeDirectory('public/instructors');
        $faker = Faker::create('ar_SA');
        $allCountries = Country::whereNull('parent_id')->get();
        $allCities = City::whereNotNull('parent_id')->get();
        $arabicQualifications = [
            'دكتوراه في علوم الحاسب',
            'ماجستير في التربية',
            'بكالوريوس في الرياضيات',
            'شهادة مهنية معتمدة',
            'خبير في المجال',
            'مدرب معتمد',
            'دكتوراه في الفيزياء',
            'ماجستير إدارة أعمال',
            'ماجستير في اللغويات',
            'بكالوريوس هندسة'
        ];

        $arabicSpecializations = [
            'البرمجة',
            'الرياضيات',
            'العلوم',
            'إدارة الأعمال',
            'اللغات',
            'الفنون',
            'التاريخ',
            'التقنية',
            'الهندسة',
            'التصميم'
        ];

        $arabicNames = [
            'محمد أحمد', 'أحمد علي', 'علي حسن', 'حسن محمود', 'محمود خالد',
            'خالد عمر', 'عمر ياسر', 'ياسر وليد', 'وليد رامي', 'رامي كريم',
            'فاطمة محمد', 'نورا أحمد', 'سارة خالد', 'لمى علي', 'هناء محمود'
        ];

        for ($i = 1; $i <= 10; $i++) {
            $country = $allCountries->random();
            $city = $allCities->where('parent_id', $country->id)->random();

            $instructor = Instructor::create([
                'name' => $arabicNames[array_rand($arabicNames)],
                'email' => $faker->unique()->safeEmail,
                'phone' => '9665' . rand(10000000, 99999999), // Saudi format
                'city_id' => $country->id,
                'region_id' => $city->id,
                'address1' => 'شارع ' . $faker->streetName,
                'experience' => rand(1, 15),
                'qualifications' => $arabicQualifications[array_rand($arabicQualifications)] . '، ' .
                    $arabicQualifications[array_rand($arabicQualifications)],
                'course_percentage' => rand(50, 90),
                'bio' => 'مدرس متخصص في مجال ' . $arabicSpecializations[array_rand($arabicSpecializations)] .
                    ' مع خبرة تزيد عن ' . rand(1, 15) . ' سنوات في التدريس. ' .
                    'حاصل على عدة شهادات في التخصص وله العديد من الإنجازات في المجال.',
            ]);

        }

        $this->command->info('تم إنشاء 10 مدربين عرب بنجاح!');
    }

}
