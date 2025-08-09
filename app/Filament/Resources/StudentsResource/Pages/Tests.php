<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Models\Course;
use App\Models\CourseTest;
use App\Models\CourseTestResult;
use App\Models\CourseTestsResults;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;

class Tests extends Page implements HasTable
{
    // use InteractsWithForms;
    use InteractsWithTable;
    protected static string $resource = StudentsResource::class;
    protected static string $view = 'filament.resources.students-resource.pages.tests';

    public $tap = 'tests';
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
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CourseTestsResults::query()
                    ->where('student_id', $this->record)
                    ->with(['test.course', 'student'])
            )
            ->columns([


                TextColumn::make('test.title')
                    ->label(trans('common.TestTitle'))
                    ->searchable()
                    ->sortable(),


                TextColumn::make('test.course.name')
                    ->label(trans('common.Course'))
                    ->searchable()
                    ->sortable(),



                TextColumn::make('test.test_date')
                    ->label(trans('common.TestDate'))
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
