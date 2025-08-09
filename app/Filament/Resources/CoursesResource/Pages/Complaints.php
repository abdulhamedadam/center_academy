<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseAssignments;
use App\Models\CourseComplaints;
use App\Models\CourseTests;
use App\Models\Students;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput as TextInputAlias;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Collection;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Complaints extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.complaints';

    public $tap = 'complaints';
    public $files = [];
    public $complaint, $student_id;
    public $record, $course;
    protected CourseService $courseService;
    protected StudentService $studentService;
    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->course = $this->courseService->get_course($record);
    }

    //------------------------------------------------------------------------------
    protected function getFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    Grid::make(2)
                    ->schema([
                        Select::make('student_id')
                            ->label(__('common.student'))
                            ->required()
                            ->options(
                                Students::query()
                                    ->pluck('full_name', 'id')
                            )
                            ->searchable()
                            ->preload(),

                    ]),


                    Textarea::make('complaint')
                        ->label(__('common.ComplaintDetails'))
                        ->required()
                        ->columnSpanFull()
                        ->rows(10),
                    Actions::make([
                        Action::make('save')
                            ->label(__('common.Add'))
                            ->action('save_complaints')
                            ->color('primary')
                            ->icon('heroicon-o-plus')
                    ])
                        ->alignEnd()
                        ->extraAttributes(['class' => 'mt-6'])
                ])
        ];
    }

    //------------------------------------------------------------------------------
    public function save_complaints()
    {
        $validatedData = $this->form->getState();
        $data = [
            'course_id' => $this->record,
            'student_id' => $this->student_id,
            'complaint' => $this->complaint,
        ];
        $test = CourseComplaints::create($data);

        Notification::make()
            ->title('Material added successfully')
            ->success()
            ->send();
        $this->form->fill();
        $this->dispatch('refresh');
    }

    //------------------------------------------------------------------------------
    public function table(Table $table): Table
    {
        return $table
            ->query(CourseComplaints::where('course_id', $this->record)->with(['student']))
            ->columns([
                TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('complaint')
                    ->label('Complaint')
                    ->limit(50),


                TextColumn::make('created_at')
                    ->label('Submitted On')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'resolved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form([
                        TextInputAlias::make('student.name')
                            ->label('Student')
                            ->disabled(),

                        Textarea::make('complaint')
                            ->label('Complaint Details')
                            ->disabled()
                            ->columnSpanFull()
                            ->rows(10),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'resolved' => 'Resolved',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),

                        Textarea::make('resolution')
                            ->label('Resolution Notes')
                            ->columnSpanFull()
                            ->rows(3),
                    ]),

                Tables\Actions\DeleteAction::make(),
            ]);


    }
}
