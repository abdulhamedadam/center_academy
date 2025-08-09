<?php

namespace Database\Seeders;

use App\Models\CrmLeadsStatus;
use Illuminate\Database\Seeder;

class CrmLeadsStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'جديد',
                'color' => 'primary',
            ],
            [
                'name' => 'تم التواصل',
                'color' => 'info',
            ],
            [
                'name' => 'مؤهل',
                'color' => 'success',
            ],
            [
                'name' => 'غير مؤهل',
                'color' => 'danger',
            ],
            [
                'name' => 'متابعة',
                'color' => 'warning',
            ],
            [
                'name' => 'تم التحويل',
                'color' => 'success',
            ],
            [
                'name' => 'مرفوض',
                'color' => 'danger',
            ],
        ];

        foreach ($statuses as $status) {
            CrmLeadsStatus::create($status);
        }
    }
} 