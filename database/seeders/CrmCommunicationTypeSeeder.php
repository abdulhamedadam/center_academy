<?php

namespace Database\Seeders;

use App\Models\CrmCommunicationType;
use Illuminate\Database\Seeder;

class CrmCommunicationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'هاتف',
            'منزل',
            'محادثة',
            'بريد إلكتروني',
            'فيديو',
            'رسالة',
            'رابط',
        ];

        foreach ($types as $name) {
            CrmCommunicationType::create([
                'name' => $name,
            
            ]);
        }
    }
} 