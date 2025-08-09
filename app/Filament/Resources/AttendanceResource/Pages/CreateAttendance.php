<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\CourseAttendance;
use App\Models\CourseAttendanceDetails;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    public function getHeading(): string
    {
        return __('common.Create Attendance');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Remove attendance data from the main record
        $attendanceData = $data['attendance'] ?? [];
        unset($data['attendance']);

        // Create the main attendance record
        $mainAttendance = CourseAttendance::create([
            'course_id' => $data['course_id'],
            'date' => $data['date'],
            'group_id' => $data['group_id'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // Save attendance details
        foreach ($attendanceData as $attendance) {
            CourseAttendanceDetails::create([
                'attendance_id' => $mainAttendance->id,
                'student_id' => $attendance['student_id'],
                'status' => $attendance['status'],
            ]);
        }

        

        return $mainAttendance;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 