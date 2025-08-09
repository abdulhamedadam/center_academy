<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseGroups;
use App\Models\CourseMaterials;
use App\Models\CourseSchedule;
use App\Models\CourseGroupDays;
use App\Services\CourseService;
use App\Services\StudentService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Page implements HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $tap = 'schedules';
    public $record, $course, $group;
    /*public  $duration, $start_time, $date, $end_time, $schedules, $weeks, $startDate, $endDate, $groupedSchedules, $editingScheduleId,$status='scheduled';
    public $monthlySchedule = [];
    public $editingSchedule = null;
    public $edit_date;
    public $edit_start_time;
    public $edit_end_time;
    public $nextScheduleDate;
    public $editFormState = [];*/

    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.schedules';
    protected CourseService $courseService;

    /****************************************************/
    public function mount($record)
    {
        $this->record = $record;
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->course = $this->courseService->get_course($record);
        $this->group = $this->courseService->get_groups($record);
        /*  $this->duration = optional($this->course)->duration;
          $this->date = optional($this->course)->start_date;

          $this->prepareScheduleData();
          $this->form->fill();
          $this->editForm->fill();*/
    }

    //------------------------------------------------------------------------------------------------------------------
    public function table(Table $table): Table
    {
        return $table
            ->query(CourseGroups::where('course_id', $this->record)->with('groupDays'))
            ->columns([
                TextColumn::make('name')
                    ->label('Group Name'),

                // For each day column
                ...$this->getDayColumns(),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Repeater::make('group_days')
                            ->label('Days')
                            ->schema([
                                Select::make('day')
                                    ->options([
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                    ])
                                    ->required(),
                                TimePicker::make('start_time')
                                    ->required(),
                                TimePicker::make('end_time')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->reorderable(false)
                            ->addActionLabel('Add Day'),
                    ])
                    ->using(function (Model $record, array $data): Model {
                        $groupDays = $data['group_days'] ?? [];
                        unset($data['group_days']);

                        // Delete existing days
                        $record->groupDays()->delete();

                        // Create new days
                        foreach ($groupDays as $day) {
                            CourseGroupDays::create([
                                'group_id' => $record->id,
                                'course_id' => $this->record,
                                'day' => $day['day'],
                                'start_time' => $day['start_time'],
                                'end_time' => $day['end_time'],
                            ]);
                        }

                        return $record;
                    })
                    ->formData([
                        'group_days' => function (Model $record): array {
                            $groupDays = $record->groupDays()->get();
                            
                            return $groupDays->map(function ($day) {
                                return [
                                    'day' => $day->day,
                                    'start_time' => $day->start_time,
                                    'end_time' => $day->end_time,
                                ];
                            })->toArray();
                        }
                    ])
            ])
            ->emptyStateHeading('No groups found');
    }

    protected function getDayColumns(): array
    {
        $days = [
            'saturday' => 'Sat',
            'sunday' => 'Sun',
            'monday' => 'Mon',
            'tuesday' => 'Tue',
            'wednesday' => 'Wed',
            'thursday' => 'Thu',
            'friday' => 'Fri',
        ];

        $columns = [];

        foreach ($days as $dayKey => $dayLabel) {
            $columns[] = TextColumn::make($dayKey)
                ->label($dayLabel)
                ->getStateUsing(function ($record) use ($dayKey) {
                    $groupDay = $record->groupDays->firstWhere('day', $dayKey);
                    if ($groupDay) {
                        $start = $groupDay->start_time ? date('H:i', strtotime($groupDay->start_time)) : '';
                        $end = $groupDay->end_time ? date('H:i', strtotime($groupDay->end_time)) : '';
                        $instructor = $record->instructor->name ?? '';

                        return "<div style='line-height: 1.5;'>
            <div style='color: #00bb00'>✓</div>
            <div>{$start} - {$end}</div>
            <div>{$instructor}</div>
        </div>";
                    }
                    return '';
                })
                ->html()
                ->wrap()
                ->alignCenter();
        }

        return $columns;
    }

    //------------------------------------------------------------------------------------------------------------------
    /* protected function prepareScheduleData()
     {
         $schedules = CourseSchedule::where('course_id', $this->record)
             ->orderBy('date')
             ->orderBy('start_time')
             ->get();

         $this->nextScheduleDate = $schedules->first()?->date;
         //dd($this->nextScheduleDate);


         if ($schedules->isEmpty()) {
             return;
         }
         Carbon::setLocale('ar');

         $startDate = Carbon::parse($schedules->first()->date);
         $endDate = Carbon::parse($schedules->last()->date);
         $months = CarbonPeriod::create(
             $startDate->startOfMonth(),
             '1 month',
             $endDate->endOfMonth()
         );


         foreach ($months as $month) {
             $monthKey = $month->format('Y-m');
             $this->monthlySchedule[$monthKey] = [
                 'month_name' => $month->translatedFormat('F Y'),
                 'weeks' => [
                     1 => [],
                     2 => [],
                     3 => [],
                     4 => [],
                 ]
             ];
         }

         foreach ($schedules as $schedule) {
             $date = Carbon::parse($schedule->date);
             $monthKey = $date->format('Y-m');
             $weekOfMonth = ceil($date->day / 7);
             $weekOfMonth = min(max($weekOfMonth, 1), 4);

             $this->monthlySchedule[$monthKey]['weeks'][$weekOfMonth][] = [
                 'id' => $schedule->id,
                 'status' => $schedule->status,
                 'day' => $date->translatedFormat('l'),
                 'date' => $date->format('Y-m-d'),
                 'start_time' => Carbon::parse($schedule->start_time)->format('h:i A'),
                 'end_time' => Carbon::parse($schedule->end_time)->format('h:i A'),
             ];
         }
     }*/

    /****************************************************/
    /* protected function getFormSchema(): array
     {
         return [
             Card::make()
                 ->schema([
                     Grid::make(3)
                         ->schema([
                             DatePicker::make('date')
                                 ->label(__('Date'))
                                 ->required()
                                 ->native(false)
                                 ->displayFormat('Y-m-d')
                                 ->closeOnDateSelection(),

                             TimePicker::make('start_time')
                                 ->label(__('Start Time'))
                                 ->required()
                                 ->seconds(false)
                                 ->native(false),

                             TimePicker::make('end_time')
                                 ->label(__('End Time'))
                                 ->required()
                                 ->seconds(false)
                                 ->native(false)
                                 ->afterOrEqual('start_time'),

                             TextInput::make('duration')
                                 ->label(__('Duration (months)'))
                                 ->default(3)
                                 ->numeric()
                                 ->required(),

                             Actions::make([
                                 Action::make('save')
                                     ->label(__('common.Add'))
                                     ->action(function (array $data) {
                                         $validatedData = $this->form->getState();
                                         $originalDate = Carbon::parse($validatedData['date']);
                                         $startTime = $validatedData['start_time'];
                                         $endTime = $validatedData['end_time'];
                                         $durationMonths = (int)$validatedData['duration'];
                                         $weeksToCreate = $durationMonths * 4;

                                         for ($week = 0; $week < $weeksToCreate; $week++) {
                                             $schedule = new CourseSchedule();
                                             $schedule->date = $originalDate->copy()->addWeeks($week)->format('Y-m-d');
                                             $schedule->start_time = $startTime;
                                             $schedule->end_time = $endTime;
                                             $schedule->course_id = $this->record;
                                             $schedule->save();
                                         }

                                         Notification::make()
                                             ->title('Schedules added successfully')
                                             ->success()
                                             ->send();
                                         $this->dispatch('refresh');
                                     })
                                     ->color('primary')
                                     ->icon('heroicon-o-plus')
                             ])
                                 ->alignStart()
                                 ->extraAttributes(['class' => 'mt-6'])
                         ])
                 ])
         ];
     }*/

    /****************************************************/
    /* protected function getTableQuery()
     {
         return CourseSchedule::query()->where('course_id', $this->record);
     }*/

    /****************************************************/
    /* public function editSchedule($scheduleId)
     {
         $this->editingScheduleId = $scheduleId;
         $schedule = CourseSchedule::find($scheduleId);

         if ($schedule) {
             $this->editFormState = [
                 'edit_date' => $schedule->date,
                 'edit_start_time' => $schedule->start_time,
                 'edit_end_time' => $schedule->end_time,
                 'status' => $schedule->status,
             ];
             $this->edit_date = $schedule->date;
             $this->edit_start_time = $schedule->start_time;
             $this->edit_end_time = $schedule->end_time;
             $this->status = $schedule->status;
             $this->editForm->fill($this->editFormState);
         }
     }*/

    /****************************************************/
    /* public function saveSchedule($scheduleId)
     {
         $data = $this->editForm->getState();
         $schedule = CourseSchedule::find($scheduleId);

         if ($schedule) {
             $schedule->date = $data['edit_date'];
             $schedule->start_time = $data['edit_start_time'];
             $schedule->end_time = $data['edit_end_time'];
             $schedule->status = $data['status'];

             $schedule->save();

             Notification::make()
                 ->title('Schedule updated successfully')
                 ->success()
                 ->send();

             $this->dispatch('refresh');
         }
     }*/

    /****************************************************/
    /* public function editForm(Form $form): Form
     {
         return $form
             ->schema([
                 Grid::make(3),
                 DatePicker::make('edit_date')
                     ->label('Date')
                     ->required()
                     ->native(false)
                     ->displayFormat('Y-m-d')
                     ->closeOnDateSelection(),

                 TimePicker::make('edit_start_time')
                     ->label('Start Time')
                     ->required()
                     ->seconds(false)
                     ->native(false),

                 TimePicker::make('edit_end_time')
                     ->label('End Time')
                     ->required()
                     ->seconds(false)
                     ->native(false)
                     ->afterOrEqual('edit_start_time'),

                 Select::make('status')
                     ->label(__('Status'))
                     ->options([
                         'scheduled' => __('common.Scheduled'),
                         'completed' => __('common.Completed'),
                         'cancelled' => __('common.Cancelled'),
                         'postponed' => __('common.Postponed'),
                     ])
                     ->default('scheduled')
                     ->required(),
             ])
             ->statePath('editFormState');
     }*/

    /****************************************************/
    /* protected function getForms(): array
     {
         return [
             'form',
             'editForm',
         ];
     }*/
}
