<?php

namespace App\Filament\Resources\CourseGroupResource\Pages;

use App\Filament\Resources\CourseGroupResource;
use App\Models\CourseGroupDays;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCourseGroup extends EditRecord
{
    protected static string $resource = CourseGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $group = $this->record;
        $groupDays = $group->groupDays()->get();
        
        $data['group_days'] = $groupDays->map(function ($day) {
            return [
                'day' => $day->day,
                'start_time' => $day->start_time,
                'end_time' => $day->end_time,
            ];
        })->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $groupDays = $data['group_days'] ?? [];
        unset($data['group_days']);

        $record->update($data);

        // Delete existing days
        $record->groupDays()->delete();

        // Create new days
        foreach ($groupDays as $day) {
            CourseGroupDays::create([
                'group_id' => $record->id,
                'course_id' => $data['course_id'],
                'day' => $day['day'],
                'start_time' => $day['start_time'],
                'end_time' => $day['end_time'],
            ]);
        }

        return $record;
    }
} 