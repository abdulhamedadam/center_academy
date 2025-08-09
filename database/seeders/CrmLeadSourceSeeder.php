<?php

namespace Database\Seeders;

use App\Models\CrmLeadSource;
use Illuminate\Database\Seeder;

class CrmLeadSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            [
                'name' => 'فيسبوك',
            ],
            [
                'name' => 'إنستجرام',
            ],
            [
                'name' => 'تويتر',
            ],
            [
                'name' => 'لينكد إن',
            ],
            [
                'name' => 'موقع إلكتروني',
            ],
            [
                'name' => 'إحالة',
            ],
            [
                'name' => 'بريد إلكتروني',
            ],
            [
                'name' => 'هاتف',
            ],
            [
                'name' => 'وارد',
            ],
            [
                'name' => 'معرض',
            ],
            [
                'name' => 'مؤتمر',
            ],
            [
                'name' => 'أخرى',
            ],
        ];

        foreach ($sources as $source) {
            CrmLeadSource::create($source);
        }
    }
} 