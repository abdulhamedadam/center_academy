<?php

namespace App\Filament\Resources\CourseGroupResource\Pages;

use App\Filament\Resources\CourseGroupResource;
use App\Models\CourseGroupDays;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCourseGroup extends CreateRecord
{
    protected static string $resource = CourseGroupResource::class;
    
    protected array $groupDays = [];

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->groupDays = $data['group_days'] ?? [];
        unset($data['group_days']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $data = $this->data;

        foreach ($this->groupDays as $day) {
            CourseGroupDays::create([
                'group_id' => $record->id,
                'course_id' => $data['course_id'],
                'day' => $day['day'],
                'start_time' => $day['start_time'],
                'end_time' => $day['end_time'],
            ]);
        }
    }
} 