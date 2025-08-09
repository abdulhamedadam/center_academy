<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseGroupResource\Pages;
use App\Filament\Resources\CourseGroupResource\RelationManagers;
use App\Models\CourseGroup;
use App\Models\CourseGroups;
use App\Models\Courses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class CourseGroupResource extends Resource
{
    protected static ?string $model = CourseGroups::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 4;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationGroup(): string
    {
        return __('common.courses_management');
    }
    //-----------------------------------------------------------------------------------------
    protected static ?string $recordTitleAttribute = 'name';
    public static function getGlobalSearchResultUrl($model): string
    {
        return CourseGroupResource::getUrl('index');
    }
    //-----------------------------------------------------------------------------------------
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('common.name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('course_id')
                            ->label(__('common.course'))
                            ->relationship('course', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state) {
                                    $set('code', null);
                                    return;
                                }
                                $course = Courses::find($state);
                                if (!$course) {
                                    $set('code', null);
                                    return;
                                }
                                $courseCode = $course->code;
                                $groupsCount = \App\Models\CourseGroups::where('course_id', $state)->count();
                                $serial = $groupsCount + 1;
                                $newGroupCode = $courseCode . str_pad($serial, 2, '0', STR_PAD_LEFT);

                                $set('code', $newGroupCode);
                            }),

                        Forms\Components\Select::make('hall_id')
                            ->label(__('common.hall'))
                            ->relationship('hall', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('code')
                            ->label('كود الجروب')
                            ->readOnly()
                            ->required(),

                        Forms\Components\Select::make('instructor_id')
                            ->label(__('common.instructor'))
                            ->relationship('instructor', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('start_date')
                            ->label(__('common.start_date'))
                            ->native(false),

                        Forms\Components\DatePicker::make('end_date')
                            ->label(__('common.end_date'))
                            ->native(false),

                            
                        Forms\Components\TextInput::make('max_number')
                        ->label(__('common.max_students'))
                        ->numeric()
                        ->minValue(1),

                        Forms\Components\Toggle::make('status')
                        ->label(__('common.status'))
                        ->default(true)
                        ->inline(false),

                        Forms\Components\Repeater::make('group_days')
                            ->label(__('common.days'))
                            ->schema([
                                Forms\Components\Select::make('day')
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

                                Forms\Components\TimePicker::make('start_time')
                                    ->label(__('common.start_time'))
                                    ->seconds(false)
                                    ->required(),

                                Forms\Components\TimePicker::make('end_time')
                                    ->label(__('common.end_time'))
                                    ->seconds(false)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->reorderable(false)
                            ->addActionLabel(__('common.add_day'))
                            ->columnSpanFull(),


                    
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_and_code')
                    ->label(__('common.name') . ' / ' . 'كود الجروب')
                    ->getStateUsing(fn ($record) => $record->name . ' ( ' . $record->code . ' ) ')
                    ->searchable(),

                Tables\Columns\TextColumn::make('course.name')
                    ->label(__('common.course'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('hall.name')
                    ->label(__('common.hall'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('instructor.name')
                    ->label(__('common.instructor'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('common.start_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('common.end_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('days')
                    ->label(__('common.days'))
                    ->formatStateUsing(fn ($state) => implode(', ', $state))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('max_number')
                    ->label(__('common.max_students'))
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('status')
                    ->label(__('common.status')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('common.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('name_or_code')
                    ->label(__('common.name') . ' / ' . 'كود الجروب')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('search')
                            ->label(__('common.search')),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (filled($data['search'] ?? null)) {
                            $query->where(function ($q) use ($data) {
                                $q->where('name', 'like', '%' . $data['search'] . '%')
                                  ->orWhere('code', 'like', '%' . $data['search'] . '%');
                            });
                        }
                    }),
                
                \Filament\Tables\Filters\SelectFilter::make('course_id')
                    ->label(__('common.course'))
                    ->relationship('course', 'name'),

                \Filament\Tables\Filters\SelectFilter::make('instructor_id')
                    ->label(__('common.instructor'))
                    ->relationship('instructor', 'name'),

                \Filament\Tables\Filters\TernaryFilter::make('status')
                    ->label(__('common.status')),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading(__('common.edit_course_group')),

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
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCourseGroups::route('/'),
            'create' => Pages\CreateCourseGroup::route('/create'),
            'edit' => Pages\EditCourseGroup::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('common.course_groups');
    }
    //----------------------------------------------------------------------
    public static function getBreadCrumb(): string
    {
        return __('common.course_groups');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.course_groups');
    }

    public static function getLabel(): string
    {
        return __('common.course_groups');
    }

    public static function getModelLabel(): string
    {
        return __('common.course_group');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.course_groups');
    }


}
