<?php

namespace App\Filament\Resources\InstructorResource\Pages;

use App\Filament\Resources\InstructorResource;
use App\Services\CourseService;
use App\Services\InstructorService;
use App\Services\StudentService;
use Filament\Resources\Pages\Page;

class InstructorDetails extends Page
{
    protected static string $resource = InstructorResource::class;

    protected static string $view = 'filament.resources.instructor-resource.pages.instructor-details';

    public $tap = 'details';
    public $instructor;
    public $record;

    protected CourseService $courseService;
    protected StudentService $studentService;
    protected InstructorService $instructorService;

    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->instructorService = app(InstructorService::class);
        $this->instructor = $this->instructorService->get_instructor($record);
    }
}
