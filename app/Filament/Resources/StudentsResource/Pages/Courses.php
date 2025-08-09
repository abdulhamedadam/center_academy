<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Models\CourseStudents;
use App\Models\Students;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Courses extends Page implements HasTable
{
   // use InteractsWithForms;
    use InteractsWithTable;
    protected static string $resource = StudentsResource::class;
    protected static string $view = 'filament.resources.students-resource.pages.courses';

    public $tap = 'courses';
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
    public function table(Table $table)
    {
        return $table
            ->query(
                Students::query()
                    ->with(['courses','courses.level', 'courses.instructor', 'courses.category'])
                    ->where('id', $this->record)
            )
            ->columns([
                TextColumn::make('courses.name')
                    ->label(__('common.CourseName'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('courses.instructor.name')
                    ->label(__('common.Instructor'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('courses.level.name')
                    ->label(__('common.Level'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('courses.start_date')
                    ->date()
                    ->label(__('common.start_date'))
                    ->sortable(),

                TextColumn::make('courses.end_date')
                    ->date()
                    ->label(__('common.end_date'))
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state): string => \App\Models\Courses::getStatusOptions()[$state] ?? 'Unknown')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        \App\Models\Courses::STATUS_ACTIVE => 'success',
                        \App\Models\Courses::STATUS_COMPLETED => 'primary',
                        \App\Models\Courses::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Action::make('view')
//                    ->url(fn (Students $record): string => route('filament.resources.courses.view', $record->course_id))
                    ->icon('heroicon-o-eye'),
            ])
            ->emptyStateHeading('No courses found')
            ->emptyStateDescription('This student is not enrolled in any courses yet.');
    }


}
