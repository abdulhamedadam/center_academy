<?php

namespace Database\Seeders;

use App\Models\Levels;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class LevelsSeeder extends Seeder
{

    public function run()
    {
        $levels = [
            [
                'name' => 'المبتدئ',
                'description' => 'مستوى للمبتدئين بدون خبرة سابقة'
            ],
            [
                'name' => 'المتوسط',
                'description' => 'مستوى للأشخاص ذوي الخبرة الأساسية'
            ],
            [
                'name' => 'المتقدم',
                'description' => 'مستوى للمحترفين ذوي الخبرة المتوسطة'
            ],
            [
                'name' => 'الاحترافي',
                'description' => 'مستوى متخصص للمحترفين'
            ],
            [
                'name' => 'التميز',
                'description' => 'أعلى مستوى من الخبرة والإتقان'
            ]
        ];

        foreach ($levels as $level) {
            Levels::firstOrCreate(
                ['name' => $level['name']], // Check if name exists
                [
                    'description' => $level['description'],
                    'created_by' => 1
                ]
            );
        }
    }

}
