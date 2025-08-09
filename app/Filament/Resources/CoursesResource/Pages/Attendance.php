<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseAttendance;
use App\Models\CourseAttendanceDetail;
use App\Models\CourseAttendanceDetails;
use App\Models\CourseSchedule;
use App\Models\Students;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseGroups;
use App\Models\CourseStudents;
use Illuminate\Support\HtmlString;

class Attendance extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.attendance';

    public ?array $data = [];
    public $record;
    public $course;
    public $tap = 'attendance';
    public $attendance_dates;
    public $selected_group;
    public $stats = [
        'present' => 0,
        'absent' => 0,
        'total' => 0
    ];

    public function mount($record)
    {
        $this->record = $record;
        $this->course = $this->getCourse();
        $this->form->fill();
        $this->attendance_dates = $this->getAttendanceDates();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CourseAttendance::query()
                    ->where('course_id', $this->record)
                    ->when($this->selected_group, function ($query) {
                        $query->where('group_id', $this->selected_group);
                    })
                    ->with(['group', 'details.student', 'details' => function($query) {
                        $query->where('status', 1);
                    }, 'details' => function($query) {
                        $query->where('status', 2);
                    }])
                    ->withCount([
                        'details as present_count' => function($query) {
                            $query->where('status', 1);
                        },
                        'details as absent_count' => function($query) {
                            $query->where('status', 2);
                        }
                    ])
            )
            ->columns([
                TextColumn::make('date')
                    ->label(__('common.Date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('group.name')
                    ->label(__('common.Group'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('present_count')
                    ->label(__('common.Present'))
                    ->color('success')
                    ->action(
                        Action::make('viewPresent')
                            ->label(__('common.View Present Students'))
                            ->modalHeading(fn ($record) => __('common.Present Students') . ' - ' . $record->date)
                            ->modalContent(function ($record) {
                                $presentStudents = $record->details()
                                    ->where('status', 1)
                                    ->with('student')
                                    ->get()
                                    ->map(function ($detail) {
                                        return $detail->student->full_name;
                                    })
                                    ->join('<br>');
                                
                                return new HtmlString($presentStudents ?: __('common.No present students'));
                            })
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                    ),

                TextColumn::make('absent_count')
                    ->label(__('common.Absent'))
                    ->numeric()
                    ->color('danger')
                    ->action(
                        Action::make('viewAbsent')
                            ->label(__('common.View Absent Students'))
                            ->modalHeading(fn ($record) => __('common.Absent Students') . ' - ' . $record->date)
                            ->modalContent(function ($record) {
                                $absentStudents = $record->details()
                                    ->where('status', 2)
                                    ->with('student')
                                    ->get()
                                    ->map(function ($detail) {
                                        return $detail->student->full_name;
                                    })
                                    ->join('<br>');
                                
                                return new HtmlString($absentStudents ?: __('common.No absent students'));
                            })
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                    ),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->defaultSort('date', 'desc');
    }

    protected function getAttendanceDates()
    {
        return CourseAttendance::where('course_id', $this->record)
            ->when($this->selected_group, function ($query) {
                $query->where('group_id', $this->selected_group);
            })
            ->pluck('date')
            ->unique()
            ->toArray();
    }

    protected function getCourse()
    {
        return \App\Models\Courses::find($this->record);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('selected_group')
                            ->label(__('common.Group'))
                            ->options(function () {
                                return CourseGroups::where('course_id', $this->record)
                                    ->where('status', 1)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(function () {
                                $this->selected_group = $this->data['selected_group'];
                                $this->loadStudentsForDate();
                            })
                            ->columnSpan(1),

                        Select::make('selectedDate')
                            ->label('Class Date')
                            ->required()
                            ->options(function () {
                                if (!$this->selected_group) {
                                    return [];
                                }

                                $group = CourseGroups::with('groupDays')->find($this->selected_group);
                                if (!$group) {
                                    return [];
                                }

                                // Get the group's days
                                $groupDays = $group->groupDays->pluck('day')->toArray();
                                
                                // Get all dates between start and end date
                                $startDate = \Carbon\Carbon::parse($group->start_date);
                                $endDate = \Carbon\Carbon::parse($group->end_date);
                                
                                $dates = [];
                                $currentDate = $startDate->copy();
                                
                                while ($currentDate <= $endDate) {
                                    $dayName = strtolower($currentDate->format('l'));
                                    if (in_array($dayName, $groupDays)) {
                                        $date = $currentDate->format('Y-m-d');
                                        $hasAttendance = in_array($date, $this->attendance_dates);
                                        
                                        $dates[$date] = $currentDate->format('Y-m-d') . 
                                            ($hasAttendance ? ' ' . __('common.attendance_exists') : '');
                                    }
                                    $currentDate->addDay();
                                }

                                return $dates;
                            })
                            ->live()
                            ->columnSpan(1)
                            ->searchable()
                            ->columns(3)
                            ->afterStateUpdated(function () {
                                $this->loadStudentsForDate();
                            }),
                    ])->columnSpan('full'),

                Repeater::make('attendance')
                    ->schema([
                        Hidden::make('student_id'),

                        Radio::make('status')
                            ->label(function ($get, $set) {
                                $index = $get('..');
                                $studentId = $index['student_id'] ?? null;

                                return $this->getStudentName($studentId);
                            })
                            ->options([
                                CourseAttendanceDetails::STATUS_PRESENT => __('common.Present'),
                                CourseAttendanceDetails::STATUS_ABSENT => __('common.Absent'),
                            ])
                            ->default('present')
                            ->inline()
                    ])->disableItemDeletion()
                    ->disableItemMovement()
                    ->disableItemCreation()
                    ->columns(1)
                    ->grid(1)
                    ->columnSpan('full')
                    ->visible(fn(): bool => !empty($this->data['selectedDate']) && !empty($this->data['selected_group']))
                    ->columns(1)
            ])
            ->statePath('data');
    }

    protected function loadStudentsForDate()
    {
        if (empty($this->data['selectedDate']) || empty($this->data['selected_group'])) {
            return;
        }

        $selectedDate = $this->data['selectedDate'];
        $selectedGroup = $this->data['selected_group'];

        // Check if attendance exists for this date
        if (in_array($selectedDate, $this->attendance_dates)) {
            // Load existing attendance records
            $mainAttendance = CourseAttendance::where('course_id', $this->course->id)
                ->where('date', $selectedDate)
                ->where('group_id', $selectedGroup)
                ->first();

            $existingDetails = CourseAttendanceDetails::where('attendance_id', $mainAttendance->id)
                ->get()
                ->keyBy('student_id');

            $this->data['attendance'] = CourseStudents::where('course_id', $this->course->id)
                ->where('group_id', $selectedGroup)
                ->get()
                ->map(function ($student) use ($existingDetails) {
                    $existing = $existingDetails->get($student->student_id);

                    return [
                        'student_id' => $student->student_id,
                        'status' => $existing ? $existing->status : CourseAttendanceDetails::STATUS_PRESENT
                    ];
                })->toArray();
        } else {
            // Default behavior for new dates
            $this->data['attendance'] = CourseStudents::where('course_id', $this->course->id)
                ->where('group_id', $selectedGroup)
                ->get()
                ->map(function ($student) {
                    return [
                        'student_id' => $student->student_id,
                        'status' => CourseAttendanceDetails::STATUS_PRESENT
                    ];
                })->toArray();
        }
    }

    protected function getStudentName($studentId): string
    {
        if (!$studentId) {
            return 'Unknown Student';
        }

        $student = Students::find($studentId);
        return $student ? $student->full_name : 'Unknown Student';
    }

    public function submitAttendance()
    {
        $data = $this->form->getState();

        $mainAttendance = CourseAttendance::updateOrCreate([
            'course_id' => $this->course->id,
            'date' => $data['selectedDate'],
            'group_id' => $data['selected_group'],
        ], [
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        
        foreach ($data['attendance'] as $attendance) {
            CourseAttendanceDetails::updateOrCreate([
                'attendance_id' => $mainAttendance->id,
                'student_id' => $attendance['student_id'],
            ],[
                'status' => $attendance['status'],
            ]);
        }

        Notification::make()
            ->title('Attendance saved successfully')
            ->success()
            ->send();

        $this->dispatch('refresh');
    }
}
