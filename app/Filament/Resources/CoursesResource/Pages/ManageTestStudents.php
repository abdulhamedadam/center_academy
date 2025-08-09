<?php
namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseTests;
use App\Models\Students;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class ManageTestStudents extends Page implements HasForms
{
    use InteractsWithForms;

    public $record;
    public $test;
    public $students = [];
    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.manage-test-students';

    public function mount($record)
    {
        $this->record = $record;
//        $this->test = CourseTests::findOrFail($test);

        $this->form->fill([
            'students' => $this->test->students->pluck('id')->toArray()
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Students')
                    ->schema([
                        Select::make('students')
                            ->label('Select Students')
                            ->options(Students::whereHas('courses', fn($query) => $query->where('course_id', $this->record))
                                ->pluck('full_name', 'id'))
                            ->multiple()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, $state) => $set('student_marks',
                                collect($state)->mapWithKeys(fn ($id) => [
                                    $id => ['marks' => $this->test->students->find($id)->pivot->marks ?? 0]
                                ])->toArray()
                            )),

                        FileUpload::make('answer_key')
                            ->label('Answer Key')
                            ->acceptedFileTypes(['application/pdf']),

                        \Filament\Forms\Components\Repeater::make('student_marks')
                            ->label('Enter Marks')
                            ->schema([
                                Select::make('student_id')
                                    ->label('Student')
                                    ->options(Students::whereHas('courses', fn($query) => $query->where('course_id', $this->record))
                                        ->pluck('full_name', 'id'))
                                    ->disabled(),

                                TextInput::make('marks')
                                    ->label('Marks')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(fn () => $this->test->total_marks),

                                FileUpload::make('submission')
                                    ->label('Submission File')
                            ])
                            ->columns(3)
                            ->hidden(fn (Get $get) => empty($get('students')))
                    ])
            ])
            ->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            // Sync students
            $this->test->students()->sync(
                collect($data['student_marks'])->mapWithKeys(fn ($item) => [
                    $item['student_id'] => ['marks' => $item['marks']]
                ])
            );

            // Handle file uploads
            // ...
        });

        Notification::make()
            ->title('Student marks saved successfully')
            ->success()
            ->send();

      //  $this->redirect(CoursesResource::getUrl('tests', ['record' => $this->record]));
    }
}
