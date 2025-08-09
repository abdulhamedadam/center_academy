<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Models\CourseAttendance;
use App\Models\CourseAttendanceDetails;
use App\Models\CourseSchedule;
use App\Services\CourseService;
use App\Services\StudentService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Resources\Pages\Page;

class Schedules extends Page
{
    protected static string $resource = StudentsResource::class;
    protected static string $view = 'filament.resources.students-resource.pages.schedules';

    public $tap = 'schedules';
    public $student;
    public $record;
    public $calendarData = [];
    public $months = [];
    public $weeks = [];
    public $startDate;
    public $endDate;

    protected CourseService $courseService;
    protected StudentService $studentService;
    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->student = $this->studentService->get_student($record);
        $this->setDateRangeFromCourses();
        $this->prepareCalendarData();
    }

    protected function setDateRangeFromCourses()
    {

        $dateRange = CourseSchedule::query()
            ->whereHas('course.courseStudents', function($query) {
                $query->where('student_id', $this->record)
                    ->where('is_active', 1);
            })
            ->selectRaw('MIN(date) as start_date, MAX(date) as end_date')
            ->first();

        $this->startDate = $dateRange->start_date
            ? Carbon::parse($dateRange->start_date)->startOfMonth()
            : now()->startOfMonth();

        $this->endDate = $dateRange->end_date
            ? Carbon::parse($dateRange->end_date)->endOfMonth()
            : now()->endOfMonth();
    }

    protected function prepareCalendarData()
    {
        $schedules = CourseSchedule::query()
            ->whereHas('course.courseStudents', function($query) {
                $query->where('student_id', $this->record)
                    ->where('is_active', 1);
            })
            ->with(['course', 'instructor'])
            ->whereNotNull('date')
            ->whereDate('date', '>=', $this->startDate)
            ->whereDate('date', '<=', $this->endDate)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });


        $this->months = [];
        $this->weeks = [];
        $this->calendarData = [];


        $currentMonth = $this->startDate->copy()->startOfMonth();
        while ($currentMonth <= $this->endDate) {
            $monthKey = $currentMonth->format('F Y');
            $this->months[$monthKey] = [
                'start' => $currentMonth->copy(),
                'end' => $currentMonth->copy()->endOfMonth(),
                'weeks' => []
            ];


            for ($weekNum = 1; $weekNum <= 4; $weekNum++) {
                $this->months[$monthKey]['weeks'][] = 'الاسبوع ' . $this->arabicWeekNumber($weekNum);
            }

            $currentMonth->addMonth();
        }


        $currentDate = $this->startDate->copy();
        while ($currentDate <= $this->endDate) {
            $weekStart = $currentDate->copy()->startOfWeek(Carbon::SATURDAY);
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::FRIDAY);

            $weekKey = $weekStart->format('Y-m-d');
            $this->calendarData[$weekKey] = [
                'start' => $weekStart,
                'end' => $weekEnd,
                'days' => []
            ];


            for ($i = 0; $i < 7; $i++) {
                $dayDate = $weekStart->copy()->addDays($i);
                $dayKey = $dayDate->format('Y-m-d');

                $this->calendarData[$weekKey]['days'][$dayKey] = [
                    'date' => $dayDate,
                    'dayName' => $this->arabicDayName($dayDate->dayOfWeek),
                    'schedules' => $schedules->get($dayKey, [])
                ];
            }

            $currentDate = $weekEnd->addDay();
        }
    }

    protected function arabicDayName(int $dayOfWeek): string
    {
        $days = [
            'السبت',
            'الأحد',
            'الإثنين',
            'الثلاثاء',
            'الأربعاء',
            'الخميس',
            'الجمعة'
        ];
        return $days[$dayOfWeek] ?? '';
    }

    protected function arabicWeekNumber(int $weekNum): string
    {
        $arabicNumbers = [
            'الأول',
            'الثاني',
            'الثالث',
            'الرابع'
        ];
        return $arabicNumbers[$weekNum - 1] ?? '';
    }

    //------------------------------------------------------------------------------------------------------------------
    // In your Schedules.php class (add this method)
    protected function getAttendanceStatus($courseId, $date, $studentId)
    {
        $attendance = CourseAttendance::where('course_id', $courseId)
            ->where('date', $date)
            ->first();

        if (!$attendance) {
            return ['status' => 'not_marked', 'details' => null];
        }

        $detail = CourseAttendanceDetails::where('attendance_id', $attendance->id)
            ->where('student_id', $studentId)
            ->first();

        return [
            'status' => $detail->status ?? 'not_marked',
            'details' => $detail
        ];
    }
}
