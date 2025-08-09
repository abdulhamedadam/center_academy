<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseComplaints;
use App\Models\CourseGroups;
use App\Models\Instructor;
use App\Models\Students;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select as FormsSelect;
use App\Models\CourseGroupDays;

class Groups extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.groups';

    public $tap = 'groups';
    public $days = [];
    public $name, $course_id, $start_date, $end_date, $instructor_id, $end_time, $start_time, $max_number, $status;
    public $record, $course, $hall_id;
    public $group_days = [];
    protected CourseService $courseService;
    protected StudentService $studentService;
    protected $listeners = ['refreshForm' => '$refresh'];

    //------------------------------------------------------------------------------------------------------------------
    public function mount($record)
    {
        $this->record = $record;
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
                    TextInput::make('name')
                        ->label(__('common.name'))
                        ->required()
                        ->maxLength(255),

                    Select::make('instructor_id')
                        ->label(__('common.instructor'))
                        ->options(function () {
                            return Instructor::orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('hall_id')
                        ->label(__('common.hall'))
                        ->options(function () {
                            return \App\Models\Hall::orderBy('name')->pluck('name', 'id')->toArray();
                        })
                        ->searchable()
                        ->preload()
                        ->required(),

                    DatePicker::make('start_date')
                        ->label(__('common.start_date'))
                        ->native(false),

                    DatePicker::make('end_date')
                        ->label(__('common.end_date'))
                        ->native(false),


                    TextInput::make('max_number')
                        ->label(__('common.max_students'))
                        ->numeric()
                        ->minValue(1),

                    Toggle::make('status')
                        ->label(__('common.status'))
                        ->default(true)
                        ->inline(false),

                    Repeater::make('group_days')
                        ->label(__('common.days'))
                        ->schema([
                            FormsSelect::make('day')
                                ->label(__('common.day'))
                                ->options([
                                    'sunday' => __('common.sunday'),
                                    'monday' => __('common.monday'),
                                    'tuesday' => __('common.tuesday'),
                                    'wednesday' => __('common.wednesday'),
                                    'thursday' => __('common.thursday'),
                                    'friday' => __('common.friday'),
                                    'saturday' => __('common.saturday'),
                                ])
                                ->required(),

                            TimePicker::make('start_time')
                                ->label(__('common.start_time'))
                                ->seconds(false)
                                ->required(),

                            TimePicker::make('end_time')
                                ->label(__('common.end_time'))
                                ->seconds(false)
                                ->required(),
                        ])
                        ->columns(3)
                        ->defaultItems(1)
                        ->minItems(1)
                        ->reorderable(false)
                        ->columnSpanFull()
                        ->addActionLabel(__('common.add_day')),



                    Actions::make([
                        Action::make('save')
                            ->label(__('common.Add'))
                            ->action('save_group')
                            ->color('primary')
                            ->icon('heroicon-o-plus')
                    ])
                        ->alignEnd()
                        ->extraAttributes(['class' => 'mt-6'])
                ])
                ->columns(3),
        ];
    }
    //------------------------------------------------------------------------------------------------------------------
    public function save_group()
    {
        $validatedData = $this->form->getState();
        $groupDays = $validatedData['group_days'] ?? [];
        unset($validatedData['group_days']);

        $data = [
            'course_id' => $this->record,
            'instructor_id' => $this->instructor_id,
            'hall_id' => $this->hall_id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'max_number' => $this->max_number,
            'status' => $this->status,
        ];

        $group = CourseGroups::create($data);

        // Create group days
        foreach ($groupDays as $day) {
            CourseGroupDays::create([
                'group_id' => $group->id,
                'course_id' => $this->record,
                'day' => $day['day'],
                'start_time' => $day['start_time'],
                'end_time' => $day['end_time'],
            ]);
        }

        Notification::make()
            ->title('تم إنشاء الجروب بنجاح')
            ->success()
            ->send();
        $this->form->fill();
        $this->dispatch('refresh');
    }
    //------------------------------------------------------------------------------------------------------------------
    public function table(Table $table): Table
    {
        return $table
            ->query(CourseGroups::where('course_id', $this->record)->with(['course', 'instructor', 'groupDays']))
            ->columns([
                TextColumn::make('name')
                    ->label(__('common.name'))
                    ->searchable(),

                TextColumn::make('course.name')
                    ->label(__('common.course'))
                    ->searchable(),


                TextColumn::make('hall.name')
                    ->label(__('common.hall'))
                    ->searchable(),

                TextColumn::make('instructor.name')
                    ->label(__('common.instructor'))
                    ->searchable(),

                TextColumn::make('start_date')
                    ->label(__('common.start_date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label(__('common.end_date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('groupDays')
                    ->label(__('common.days'))
                    ->formatStateUsing(function ($record) {
                        return $record->groupDays->map(function ($day) {
                            return sprintf(
                                '<div>%s (%s - %s)</div>',
                                __('common.' . $day->day),
                                date('h:i A', strtotime($day->start_time)),
                                date('h:i A', strtotime($day->end_time))
                            );
                        })->join('');
                    })
                    ->html(),

                TextColumn::make('max_number')
                    ->label(__('common.max_students'))
                    ->sortable(),

                ToggleColumn::make('status')
                    ->label(__('common.status')),

                TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('common.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('course_id')
                    ->label(__('common.course'))
                    ->relationship('course', 'name'),

                SelectFilter::make('instructor_id')
                    ->label(__('common.instructor'))
                    ->relationship('instructor', 'name'),

                TernaryFilter::make('status')
                    ->label(__('common.status')),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading(__('common.edit_course_group'))
                    ->form([
                        TextInput::make('name')
                            ->label(__('common.name'))
                            ->required()
                            ->maxLength(255),

                        Select::make('instructor_id')
                            ->label(__('common.instructor'))
                            ->options(function () {
                                return Instructor::orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('hall_id')
                            ->label(__('common.hall'))
                            ->options(function () {
                                return \App\Models\Hall::orderBy('name')->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('start_date')
                            ->label(__('common.start_date'))
                            ->native(false),

                        DatePicker::make('end_date')
                            ->label(__('common.end_date'))
                            ->native(false),

                        Repeater::make('group_days')
                            ->label(__('common.days'))
                            ->schema([
                                FormsSelect::make('day')
                                    ->label(__('common.day'))
                                    ->options([
                                        'sunday' => __('common.sunday'),
                                        'monday' => __('common.monday'),
                                        'tuesday' => __('common.tuesday'),
                                        'wednesday' => __('common.wednesday'),
                                        'thursday' => __('common.thursday'),
                                        'friday' => __('common.friday'),
                                        'saturday' => __('common.saturday'),
                                    ])
                                    ->required(),

                                TimePicker::make('start_time')
                                    ->label(__('common.start_time'))
                                    ->seconds(false)
                                    ->required(),

                                TimePicker::make('end_time')
                                    ->label(__('common.end_time'))
                                    ->seconds(false)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->reorderable(false)
                            ->addActionLabel(__('common.add_day')),

                        TextInput::make('max_number')
                            ->label(__('common.max_students'))
                            ->numeric()
                            ->minValue(1),

                        Toggle::make('status')
                            ->label(__('common.status'))
                            ->inline(false),
                    ]),

                DeleteAction::make(),
            ]);
    }
}
