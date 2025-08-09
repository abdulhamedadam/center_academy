<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseTests;
use App\Models\CourseTestsResults;
use App\Models\Students;
use App\Services\CourseService;
use App\Services\StudentService;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\DB;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CourseTestsPage extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.course-tests-page';
    public $tap = 'tests';
    public $files = [];
    public $test_date, $start_time, $end_time, $total_marks, $test_title, $description;
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


    //------------------------------------------------------------------------------------------------------------------
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

                            DatePicker::make('test_date')
                                ->label(__('common.TestDate'))
                                ->required(),

                            TimePicker::make('start_time')
                                ->label(__('common.StartTime'))
                                ->required(),

                        ]),

                    Grid::make(3)
                        ->schema([


                            TimePicker::make('end_time')
                                ->label(__('common.EndTime'))
                                ->required(),


                            TextInput::make('total_marks')
                                ->label(__('common.TotalMarks'))
                                ->numeric()
                                ->required(),


                            SpatieMediaLibraryFileUpload::make('files')
                                ->collection('tests')
                                ->label(__('common.file'))
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
                            ->action('save_tests')
                            ->color('primary')
                            ->icon('heroicon-o-plus')
                    ])
                        ->alignEnd()
                        ->extraAttributes(['class' => 'mt-6'])
                ])
        ];
    }

    //------------------------------------------------------------------------------------------------------------------
    protected function getTableQuery()
    {
        return CourseTests::where('course_id', $this->record)
            ->with('createdBy');
    }

    //------------------------------------------------------------------------------------------------------------------
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->searchable()
                ->sortable()
                ->label(__('common.TestTitle')),

            TextColumn::make('test_date')
                ->date('d M Y')
                ->sortable()
                ->label(__('common.Date')),

            TextColumn::make('start_time')
                ->time('h:i A')
                ->label(__('common.StartTime')),

            TextColumn::make('end_time')
                ->time('h:i A')
                ->label(__('common.EndTime')),

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
                    return $data;
                })
                ->using(function (CourseTests $record, array $data): CourseTests {
                    $start = \Carbon\Carbon::parse($data['start_time']);
                    $end = \Carbon\Carbon::parse($data['end_time']);
                    $data['duration'] = $start->diffInMinutes($end);
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
                                $testId = CourseTests::where('course_id', $this->record)->first()->id;
                                $studentsWithResults = CourseTestsResults::where('test_id', $testId)
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

                        Textarea::make('feedback')
                            ->label('Feedback')
                            ->rows(1)
                            ->columnSpan(1),
                    ])->columns(3),


                    Livewire::make(\App\Http\Livewire\ResultsTable::class, [
                        'testId' =>@CourseTests::where('course_id',$this->record)->first()->id,
                    ])
                ])
                ->action(function (CourseTests $record, array $data) {
                    $testResult = CourseTestsResults::create([
                        'test_id' => $record->id,
                        'student_id' => $data['student_id'],
                        'grade' => $data['grade'],
                        'feedback' => $data['feedback'],
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


    //------------------------------------------------------------------------------------------------------------------
    public function save_tests()
    {

        $validatedData = $this->form->getState();
        $start = Carbon::parse($validatedData['start_time']);
        $end = Carbon::parse($validatedData['end_time']);
        $duration = $start->diffInMinutes($end);
        $data = [
            'course_id' => $this->record,
            'title' => $validatedData['test_title'],
            'description' => $validatedData['description'],
            'test_date' => $validatedData['test_date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'duration' => $duration,
            'total_marks' => $validatedData['total_marks'],
            'created_by' => auth()->id(),
            'status' => CourseTests::DRAFT,
        ];

        //dd($this->form->getComponent('files')->getState());
        $test = CourseTests::create($data);

        if ($this->form->getComponent('files')->getState()) {
            foreach ($this->form->getComponent('files')->getState() as $file) {
                $test->addMedia($file->getRealPath())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('tests');
            }
        }
        Notification::make()
            ->title('Material added successfully')
            ->success()
            ->send();
        $this->form->fill();
        $this->dispatch('refresh');
    }

}
