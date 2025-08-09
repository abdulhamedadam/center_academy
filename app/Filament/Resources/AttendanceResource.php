<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\CourseAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use App\Models\CourseGroups;
use App\Models\CourseStudents;
use App\Models\Students;
use App\Models\CourseAttendanceDetails;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AttendanceResource extends Resource
{
    protected static ?string $model = CourseAttendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 6;
    public static function getNavigationGroup(): string
    {
        return __('common.courses_management');
    }


   

    public static function getNavigationLabel(): string
    {
        return __('common.Attendance');
    }

    public static function getModelLabel(): string
    {
        return __('common.Attendance');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.Attendances');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('course_id')
                            ->label(__('common.Course'))
                            ->relationship('course', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('group_id', null)),

                        Select::make('group_id')
                            ->label(__('common.Group'))
                            ->relationship('group', 'name', function (Builder $query, Forms\Get $get) {
                                return $query->where('course_id', $get('course_id'));
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('attendance', []);
                            }),

                        Select::make('date')
                            ->label(__('common.Date'))
                            ->required()
                            ->options(function (Forms\Get $get) {
                                $groupId = $get('group_id');
                                if (!$groupId) {
                                    return [];
                                }

                                $group = CourseGroups::with('groupDays')->find($groupId);
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
                                        $hasAttendance = CourseAttendance::where('group_id', $groupId)
                                            ->where('date', $date)
                                            ->exists();
                                        
                                        $dates[$date] = $currentDate->format('Y-m-d') . 
                                            ($hasAttendance ? ' ' . __('common.attendance_exists') : '');
                                    }
                                    $currentDate->addDay();
                                }

                                return $dates;
                            })
                            ->live()
                            ->searchable()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $groupId = $get('group_id');
                                $courseId = $get('course_id');
                                
                                if (!$groupId || !$courseId || !$state) {
                                    $set('attendance', []);
                                    return;
                                }

                                // Check if attendance exists for this date
                                $existingAttendance = CourseAttendance::where('group_id', $groupId)
                                    ->where('date', $state)
                                    ->first();

                                if ($existingAttendance) {
                                    // Load existing attendance records
                                    $existingDetails = CourseAttendanceDetails::where('attendance_id', $existingAttendance->id)
                                        ->get()
                                        ->keyBy('student_id');

                                    $students = CourseStudents::where('course_id', $courseId)
                                        ->where('group_id', $groupId)
                                        ->get()
                                        ->map(function ($student) use ($existingDetails) {
                                            $existing = $existingDetails->get($student->student_id);
                                            return [
                                                'student_id' => $student->student_id,
                                                'status' => $existing ? $existing->status : CourseAttendanceDetails::STATUS_PRESENT
                                            ];
                                        })->toArray();
                                } else {
                                    // Load default attendance for new date
                                    $students = CourseStudents::where('course_id', $courseId)
                                        ->where('group_id', $groupId)
                                        ->get()
                                        ->map(function ($student) {
                                            return [
                                                'student_id' => $student->student_id,
                                                'status' => CourseAttendanceDetails::STATUS_PRESENT
                                            ];
                                        })->toArray();
                                }

                                $set('attendance', $students);
                            }),
                    ])->columnSpan('full'),

                Repeater::make('attendance')
                    ->schema([
                        Hidden::make('student_id'),

                        Radio::make('status')
                            ->label(function ($get) {
                                $studentId = $get('student_id');
                                return self::getStudentName($studentId);
                            })
                            ->options([
                                CourseAttendanceDetails::STATUS_PRESENT => __('common.Present'),
                                CourseAttendanceDetails::STATUS_ABSENT => __('common.Absent'),
                            ])
                            ->default(CourseAttendanceDetails::STATUS_PRESENT)
                            ->inline()
                    ])
                    ->disableItemDeletion()
                    ->disableItemMovement()
                    ->disableItemCreation()
                    ->columns(1)
                    ->grid(1)
                    ->columnSpan('full')
                    ->visible(fn (Forms\Get $get): bool => 
                        !empty($get('date')) && 
                        !empty($get('group_id'))
                    )
                    ->live()
                    ->afterStateHydrated(function (Repeater $component, $state, Forms\Get $get) {
                        if (empty($state) && !empty($get('group_id'))) {
                            $students = CourseStudents::where('course_id', $get('course_id'))
                                ->where('group_id', $get('group_id'))
                                ->get()
                                ->map(function ($student) {
                                    return [
                                        'student_id' => $student->student_id,
                                        'status' => CourseAttendanceDetails::STATUS_PRESENT
                                    ];
                                })->toArray();
                            
                            $component->state($students);
                        }
                    })
                    ->saveRelationshipsUsing(function ($record, $state) {
                        // This prevents the attendance field from being saved to the main table
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('course.name')
                    ->label(__('common.Course'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('group.name')
                    ->label(__('common.Group'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->label(__('common.Date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('present_count')
                    ->label(__('common.Present'))
                    ->state(function ($record) {
                        return $record->details()
                            ->where('status', CourseAttendanceDetails::STATUS_PRESENT)
                            ->count();
                    })
                    ->color('success')
                    ->action(
                        Tables\Actions\Action::make('viewPresent')
                            ->label(__('common.View Present Students'))
                            ->modalHeading(__('common.Present Students'))
                            ->modalContent(function ($record) {
                                $presentStudents = $record->details()
                                    ->where('status', CourseAttendanceDetails::STATUS_PRESENT)
                                    ->with('student')
                                    ->get()
                                    ->map(function ($detail) {
                                        return $detail->student->full_name;
                                    })
                                    ->join('<br>');
                                
                                return new \Illuminate\Support\HtmlString($presentStudents);
                            })
                    ),

                Tables\Columns\TextColumn::make('absent_count')
                    ->label(__('common.Absent'))
                    ->state(function ($record) {
                        return $record->details()
                            ->where('status', CourseAttendanceDetails::STATUS_ABSENT)
                            ->count();
                    })
                    ->color('danger')
                    ->action(
                        Tables\Actions\Action::make('viewAbsent')
                            ->label(__('common.View Absent Students'))
                            ->modalHeading(__('common.Absent Students'))
                            ->modalContent(function ($record) {
                                $absentStudents = $record->details()
                                    ->where('status', CourseAttendanceDetails::STATUS_ABSENT)
                                    ->with('student')
                                    ->get()
                                    ->map(function ($detail) {
                                        return $detail->student->full_name;
                                    })
                                    ->join('<br>');
                                
                                return new \Illuminate\Support\HtmlString($absentStudents);
                            })
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label(__('common.Course'))
                    ->relationship('course', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('group_id')
                    ->label(__('common.Group'))
                    ->relationship('group', 'name')
                    ->searchable(),
                Tables\Filters\Filter::make('date')
                    ->label(__('common.Date'))
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->label(__('common.Date')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['date'], fn ($q, $date) => $q->whereDate('date', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading(__('common.Edit Attendance'))
                    ->modalSubmitActionLabel(__('common.Save')),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }

    protected static function getStudentName($studentId): string
    {
        if (!$studentId) {
            return __('common.Unknown Student');
        }

        $student = Students::find($studentId);
        return $student ? $student->full_name : __('common.Unknown Student');
    }
} 