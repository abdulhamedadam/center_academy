<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Resources\Pages\Page;

class StudentDetails extends Page
{
    protected static string $resource = StudentsResource::class;
    protected static string $view = 'filament.resources.students-resource.pages.student-details';
    public $tap = 'assignments';
    public $student;
    public $record;
    protected CourseService $courseService;
    protected StudentService $studentService;
    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
        // dd($this->record);
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->student = $this->studentService->get_student($record);
    }
    //------------------------------------------------------------------------------------------------------------------

    public function getAssignmentsLink()
    {
        return route('filament.admin.resources.students.assignments', ['record' => $this->record]);
    }
}
