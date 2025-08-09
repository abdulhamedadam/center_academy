<?php

namespace App\Filament\Pages;

use App\Models\CourseGroups;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

class GlobalSchedules extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'All Schedules';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.global-schedules';

    public static function getNavigationGroup(): string
    {
        return __('common.courses_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.all_schedules');
    }

    public function getTitle(): string
    {
        return __('common.all_schedules');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CourseGroups::query()
                    ->with(['groupDays', 'course', 'instructor'])
                    ->select('tbl_course_groups.*')
                    ->join('tbl_courses', 'tbl_course_groups.course_id', '=', 'tbl_courses.id')
                    ->orderBy('tbl_courses.name')
            )
            ->groups([
               'course.name'
            ])
            ->defaultGroup('course.name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('common.group'))
                    ->searchable()
                    ->sortable(),
                ...$this->getDayColumns(),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label(__('common.group'))
                            ->required(),
                        Repeater::make('groupDays')
                            ->schema([
                                Select::make('day')
                                    ->options([
                                        'saturday' => __('common.saturday'),
                                        'sunday' => __('common.sunday'),
                                        'monday' => __('common.monday'),
                                        'tuesday' => __('common.tuesday'),
                                        'wednesday' => __('common.wednesday'),
                                        'thursday' => __('common.thursday'),
                                        'friday' => __('common.friday'),
                                    ])
                                    ->label(__('common.day'))
                                    ->required(),
                                TimePicker::make('start_time')
                                    ->label(__('common.start_time'))
                                    ->required(),
                                TimePicker::make('end_time')
                                    ->label(__('common.end_time'))
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->addActionLabel(__('common.add_day'))
                            ->label(__('common.schedule_days'))
                            ->relationship('groupDays')
                            ->afterStateHydrated(function ($component, $state) {
                                if (is_array($state)) {
                                    $component->state($state);
                                }
                            })
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getDayColumns(): array
    {
        $days = [
             'saturday' => __('common.saturday'),
                                        'sunday' => __('common.sunday'),
                                        'monday' => __('common.monday'),
                                        'tuesday' => __('common.tuesday'),
                                        'wednesday' => __('common.wednesday'),
                                        'thursday' => __('common.thursday'),
                                        'friday' => __('common.friday'),
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
                        $groupName = $record->name ?? '';

                        return "<div style='line-height: 1.5;'>
                            <div style='color: #00bb00'>✓</div>
                            <div>{$start} - {$end}</div>
                            <div>{$groupName}</div>
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
} 