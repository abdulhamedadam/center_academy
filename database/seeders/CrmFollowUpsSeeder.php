<?php

namespace Database\Seeders;

use App\Models\CrmFollowUps;
use App\Models\CrmLeads;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CrmFollowUpsSeeder extends Seeder
{
    public function run()
    {

        $leadIds = CrmLeads::pluck('id')->toArray();


        $results = [
            CrmFollowUps::INTERSTED,
            CrmFollowUps::BUSY,
            CrmFollowUps::NO_ANSWER,
            CrmFollowUps::WRONG_NUMBER,
            CrmFollowUps::NOT_INTERSTED
        ];


        $arabicNotes = [
            'تم الاتصال بالعميل وتم تحديد موعد للمتابعة',
            'العميل مهتم وسيتم التواصل معه لاحقاً',
            'العميل مشغول حالياً وطلب الاتصال لاحقاً',
            'لم يتم الرد على المكالمة',
            'العميل غير مهتم حالياً',
            'سيتم إرسال عرض مفصل عبر البريد الإلكتروني',
            'العميل يريد التفكير في العرض',
            'تم تحديد موعد زيارة لشرح الخدمات',
            'العميل مهتم وسيتم تحويله لفريق المبيعات',
            'طلب العميل معلومات إضافية'
        ];

        for ($i = 0; $i < 50; $i++) {
            $followUpDate = now()->subDays(rand(1, 30));
            $result = $results[array_rand($results)];
            $nextFollowUpDate = null;
            if ($result == CrmFollowUps::INTERSTED) {
                $nextFollowUpDate = $followUpDate->copy()->addDays(3);
            } elseif ($result == CrmFollowUps::BUSY) {
                $nextFollowUpDate = $followUpDate->copy()->addDays(7);
            } elseif ($result == CrmFollowUps::NO_ANSWER) {
                $nextFollowUpDate = $followUpDate->copy()->addDays(2);
            }

            CrmFollowUps::create([
                'lead_id' => $leadIds[array_rand($leadIds)],
                'follow_up_date' => $followUpDate,
                'next_follow_up_date' => $nextFollowUpDate,
                'note' => $arabicNotes[array_rand($arabicNotes)],
                'result' => $result,
                'added_by' => 1, // Assuming user ID 1 exists
                'created_at' => $followUpDate,
                'updated_at' => $followUpDate,
            ]);
        }
    }
}
