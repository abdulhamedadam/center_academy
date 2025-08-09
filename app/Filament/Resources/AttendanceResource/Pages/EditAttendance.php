<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\CourseAttendanceDetails;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label(__('common.Delete')),
        ];
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        // Remove attendance data from the main record
        $attendanceData = $data['attendance'] ?? [];
        unset($data['attendance']);

        // Delete existing attendance details
        CourseAttendanceDetails::where('attendance_id', $this->record->id)->delete();

        // Save new attendance details
        foreach ($attendanceData as $attendance) {
            CourseAttendanceDetails::create([
                'attendance_id' => $this->record->id,
                'student_id' => $attendance['student_id'],
                'status' => $attendance['status'],
            ]);
        }

     
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 