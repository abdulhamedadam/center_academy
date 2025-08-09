<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Models\CourseAttendanceDetails;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class Attendance extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = StudentsResource::class;
    protected static string $view = 'filament.resources.students-resource.pages.attendance';

    public $tap = 'attendance';
    public $student;
    public $record;
    public $activeTab = 'attendance';

    protected CourseService $courseService;
    protected StudentService $studentService;

    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->student = $this->studentService->get_student($record);
    }

    protected function getTableQuery()
    {
        $query = CourseAttendanceDetails::query()
            ->where('student_id', $this->record)
            ->with(['attendance.course', 'attendance.instructor'])
            ->orderBy('created_at', 'desc');

        if ($this->activeTab === 'absent') {
            $query->where('status', '2');
        } elseif ($this->activeTab === 'attendance') {
            $query->whereIn('status', ['1', '3']);
        }

        return $query;
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('attendance.date')
                ->label(__('Date'))
                ->date(),

            TextColumn::make('attendance.course.name')
                ->label(__('Course')),

        ];
    }

    //------------------------------------------------------------------------------------------------------------------
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options([
                    '1' => __('Present'),
                    '2' => __('Absent'),
                    '3' => __('Late'),
                ]),

            Filter::make('date')
                ->form([
                    DatePicker::make('date_from'),
                    DatePicker::make('date_until'),
                ])

        ];
    }

    //------------------------------------------------------------------------------------------------------------------
    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetTable();
    }

    //------------------------------------------------------------------------------------------------------------------
    protected function getStats()
    {
        $total = $this->getTableQuery()->count();
        $present = $this->getTableQuery()->where('status', '1')->count();
        $absent = $this->getTableQuery()->where('status', '2')->count();
        $late = $this->getTableQuery()->where('status', '3')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'attendance_rate' => $total > 0 ? round(($present/$total)*100, 2) : 0,
        ];
    }


    //------------------------------------------------------------------------------------------------------------------
    protected function getViewData(): array
    {
        $stats = $this->getStats();

        return [
            'stats' => $stats,
            'total' => $stats['total'],
            'present' => $stats['present'],
            'absent' => $stats['absent'],
            'late' => $stats['late'],
            'attendance_rate' => $stats['attendance_rate']
        ];
    }

}
