<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Models\CourseAssignmentsResults;
use App\Models\CourseTestsResults;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Assigments extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = StudentsResource::class;

    protected static string $view = 'filament.resources.students-resource.pages.assigments';

    public $tap = 'assignments';
    public $record;
    public $student;
    protected CourseService $courseService;
    protected StudentService $studentService;
    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->student = $this->studentService->get_student($record);
        //dd($this->student);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CourseAssignmentsResults::query()
                    ->where('student_id', $this->record)
                    ->with(['assignment.course', 'student'])
            )
            ->columns([


                TextColumn::make('assignment.title')
                    ->label(trans('common.TestTitle'))
                    ->searchable()
                    ->sortable(),


                TextColumn::make('assignment.course.name')
                    ->label(trans('common.Course'))
                    ->searchable()
                    ->sortable(),



                TextColumn::make('assignment.due_date')
                    ->label(trans('common.due_date'))
                    ->date()
                    ->sortable(),

                BadgeColumn::make('grade')
                    ->label(trans('common.Grade'))
                    ->colors([
                        'success' => ['A', 'A+'],
                        'info' => ['B', 'B+'],
                        'warning' => ['C', 'C+'],
                        'danger' => ['D', 'F'],
                    ])
                    ->sortable(),

                TextColumn::make('answer_text')
                    ->label(trans('common.answer_text'))
                    ->limit(50),
                TextColumn::make('feedback')
                    ->label(trans('common.Feedback'))
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

            ])
            ->actions([

            ])
            ->bulkActions([

            ]);
    }
}
