<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseAssignments;
use App\Models\CourseAssignmentsResults;
use App\Models\CourseTests;
use App\Models\CourseTestsResults;
use App\Models\Students;
use App\Services\CourseService;
use App\Services\StudentService;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Assignments extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.assignments';


    public $tap = 'assignments';
    public $files = [];
    public $due_date, $total_marks, $test_title, $description;
    public $record, $course;
    protected CourseService $courseService;
    protected StudentService $studentService;
    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
        // dd($this->record);
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->course = $this->courseService->get_course($record);
    }
    //---------------------------------------------------------------
    protected function getFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('test_title')
                                ->label(__('common.TestTitle'))
                                ->required()
                                ->maxLength(255),

                            DatePicker::make('due_date')
                                ->label(__('common.assignment_date'))
                                ->required(),

                            TextInput::make('total_marks')
                                ->label(__('common.total_marks'))
                                ->numeric()
                                ->required(),

                        ]),

                    Grid::make(3)
                        ->schema([

                            SpatieMediaLibraryFileUpload::make('files')
                                ->collection('assignments')
                                ->label(__('common.assignment_file'))
                                ->disk('public')
                                ->preserveFilenames()
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'video/mp4',
                                    'video/quicktime',
                                    'image/jpeg',
                                    'image/png',
                                ])
                                ->rules(['file', 'max:204800'])
                                ->multiple()
                                ->enableOpen()
                                ->enableDownload()
                                ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, Set $set, $state) {
                                    return $file;
                                }),
                        ]),

                    Textarea::make('description')
                        ->label(__('common.Description'))
                        ->columnSpan('full'),

                    Actions::make([
                        Action::make('save')
                            ->label(__('common.Add'))
                            ->action('save_assignment')
                            ->color('primary')
                            ->icon('heroicon-o-plus')
                    ])
                        ->alignEnd()
                        ->extraAttributes(['class' => 'mt-6'])
                ])
        ];
    }
    //------------------------------------------------------------------------------------------------------------------
    public function save_assignment()
    {

        $validatedData = $this->form->getState();
        $data = [
            'course_id' => $this->record,
            'title' => $validatedData['test_title'],
            'due_date' => $validatedData['due_date'],
            'description' => $validatedData['description'],
            'total_marks' => $validatedData['total_marks'],
            'created_by' => auth()->id(),
            'status' => CourseTests::DRAFT,
        ];


        //dd($this->form->getComponent('files')->getState());
        $test = CourseAssignments::create($data);

        if ($this->form->getComponent('files')->getState()) {
            foreach ($this->form->getComponent('files')->getState() as $file) {
                $test->addMedia($file->getRealPath())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('assignments');
            }
        }
        Notification::make()
            ->title('Material added successfully')
            ->success()
            ->send();
        $this->form->fill();
        $this->dispatch('refresh');
    }
    //------------------------------------------------------------------------------------------------------------------
    protected function getTableQuery()
    {
        return CourseAssignments::where('course_id', $this->record)
            ->with('createdBy');
    }
    //------------------------------------------------------------------------------------------------------------------
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->searchable()
                ->sortable()
                ->label(__('common.assignment_title')),

            TextColumn::make('due_date')
                ->date('d M Y')
                ->sortable()
                ->label(__('common.assignment_date')),


            TextColumn::make('status')
                ->formatStateUsing(fn($state) => CourseTests::getStatusName($state))
                ->label(__('common.status'))
                ->color(fn($state) => CourseTests::getStatusColor($state)),

            TextColumn::make('total_marks')
                ->label(__('common.TotalMarks')),

            TextColumn::make('createdBy.name')
                ->label('Created By'),

        ];
    }

    //------------------------------------------------------------------------------------------------------------------
    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->form($this->getFormSchema())
                ->mutateRecordDataUsing(function (array $data): array {
                    //dd($data);
                    return $data;
                })
                ->using(function (CourseAssignments $record, array $data): CourseAssignments {
                    $record->update($data);
                    return $record;
                }),

            \Filament\Tables\Actions\Action::make('manage_student_result')
                ->label('Add/Edit Student Result')
                ->icon('heroicon-o-user-plus')
                ->modalHeading('Student Test Result')
                ->form([
                    Section::make('Add/Edit Result')->schema([
                        Select::make('student_id')
                            ->label('Student')
                            ->options(function () {
                                $testId = CourseAssignments::where('course_id', $this->record)->first()->id;
                                $studentsWithResults = CourseAssignmentsResults::where('assignment_id', $testId)
                                    ->pluck('student_id');

                                return Students::whereHas('courses', fn($q) => $q->where('course_id', $this->record))
                                    ->whereNotIn('id', $studentsWithResults)
                                    ->pluck('full_name', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->live(),

                        TextInput::make('grade')
                            ->label('Marks Obtained')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(fn(Get $get) => $get('../../total_marks') ?? 100)
                            ->required(),

                        Textarea::make('answer_text')
                            ->label('answer_text')
                            ->rows(1)
                            ->columnSpan(1),
                    ])->columns(3),


                    Livewire::make(\App\Http\Livewire\AssignmentResultsTable::class, [
                        'assignment' =>@CourseAssignments::where('course_id',$this->record)->first()->id,
                    ])
                ])
                ->action(function (CourseAssignments $record, array $data) {
                    $testResult = CourseAssignmentsResults::create([
                        'assignment_id' => $record->id,
                        'student_id' => $data['student_id'],
                        'grade' => $data['grade'],
                        'answer_text' => $data['answer_text'],
                        'submitted_at' => now()
                    ]);


                    Notification::make()
                        ->title('Test result saved successfully')
                        ->success()
                        ->send();
                })

                ->modalWidth('6xl'),

            DeleteAction::make(),
        ];
    }
}
