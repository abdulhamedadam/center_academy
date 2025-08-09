<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoursesResource\Pages;
use App\Filament\Resources\CoursesResource\RelationManagers;
use App\Models\Courses;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use function Pest\ArchPresets\name;

class CoursesResource extends Resource
{
    protected static ?string $model = Courses::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 5;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $recordTitleAttribute = 'name';
    public static function getGlobalSearchResultUrl($model): string
    {
        return CoursesResource::getUrl('students', ['record' => $model->id]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code']; // Add 'code' here
    }

    public static function getGlobalSearchResultDetails($model): array
    {
        return [
            'Name' => $model->name,
            'Code' => $model->code,
        ];
    }

    public static function getNavigationGroup(): string
    {
        return __('common.courses_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Select::make('category_id')
                        ->label(__('common.category'))
                        ->relationship('Category', 'name')
                        ->required()
                        ->preload()
                        ->searchable()
                        ->columnSpan(1),

                    Select::make('level_id')
                        ->label(__('common.level'))
                        ->relationship(name: 'Level', titleAttribute: 'name')
                        ->required()
                        ->preload()
                        ->searchable()
                        ->columnSpan(1),

                    TextInput::make('name')
                        ->label(__('common.course_name'))
                        ->required()

                        ->maxLength(255)
                        ->columnSpan(1),

                    // Second Row
                    RichEditor::make('description')
                        ->label(__('common.Description'))
                        ->required()
                        ->columnSpanFull(),

                    // Third Row
                    Select::make('instructor_id')
                        ->label(__('common.instructor'))
                        ->relationship(name: 'Instructor', titleAttribute: 'name')
                        // ->required()
                        ->preload()
                        ->searchable()
                        ->columnSpan(1),

                    TextInput::make('price')
                        ->label(__('common.price'))
                        ->numeric()
                        ->required()
                        ->prefix(config('app.currency_symbol', '$'))
                        ->debounce(500) // تم إزالة live لتقليل الرندر
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::calculateTotalPrice($get, $set);
                        })
                        ->columnSpan(1),

                    Select::make('discount_type')
                        ->label(__('common.discount_type'))
                        ->options([
                            'p' => __('common.percentage'),
                            'v' => __('common.fixed'),
                        ])
                        ->live(onBlur: true) // تحديث فقط عند الخروج من الحقل
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::calculateTotalPrice($get, $set);
                        })
                        ->columnSpan(1),

                    TextInput::make('discount')
                        ->label(__('common.discount'))
                        ->numeric()
                        ->debounce(500)
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::calculateTotalPrice($get, $set);
                        })
                        ->columnSpan(1),

                    TextInput::make('total_price')
                        ->label(__('common.total_price'))
                        ->numeric()
                        ->readOnly() // بدل disabled
                        ->dehydrated(true) // تأكد أنه سيتم إرساله مع البيانات
                        ->columnSpan(1),

                    Toggle::make('status')
                        ->label(__('common.status'))
                        ->onColor('success')
                        ->offColor('danger')
                        ->default(true)
                        ->inline(false)
                        ->inlineLabel(false)
                        ->columnSpan(1),
                    TextInput::make('duration')
                        ->label(__('common.duration_months'))
                        ->numeric()
                        ->required()
                        ->step(0.5)
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $startDate = $get('start_date');
                            $duration = (float)$get('duration');

                            if ($startDate && $duration) {
                                $endDate = Carbon::parse($startDate)
                                    ->addMonthsNoOverflow((int)$duration)
                                    ->addDays(($duration - (int)$duration) * 30);
                                $set('end_date', $endDate->format('Y-m-d'));
                            }
                        })
                        ->columnSpan(1),

                  

                    TextInput::make('max_students')
                        ->label(__('common.max_students'))
                        ->numeric()
                        ->columnSpan(1),
 
                ])
                    ->columns(3)
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn (Courses $record): string => static::getUrl('groups', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.course_name'))
                    ->searchable()
                    ->color('primary')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $state . ' (' . $record->code . ')'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('common.category'))
                    ->searchable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),

                Tables\Columns\TextColumn::make('level.name')
                    ->label(__('common.level'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('instructor.name')
                    ->label(__('common.instructor'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color('warning')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('common.price'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),



                Tables\Columns\TextColumn::make('discount')
                    ->label(__('common.discount'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(function ($state, $record) {
                        if (!$state) return '-';

                        return match($record->discount_type) {
                            'p' => __('common.percentage') . ": " . $state . '%',
                            'v' => __('common.fixed') . ": " .  $state,
                            default => '-',
                        };
                    })
                    ->sortable(),


                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('common.total_price'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label(__('common.duration_months'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color('info')
                    ->suffix(' '.__('common.months')),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('common.status'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->icon(fn ($state): string => match((int) $state) {
                        1 => 'heroicon-o-lock-open',
                        2 => 'heroicon-o-lock-closed',
                        3 => 'heroicon-o-clock',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn ($state): string => match((int) $state) {
                        1 => 'success',
                        2 => 'danger',
                        3 => 'warning',
                        default => 'gray',
                    })
                    ->action(function ($record, Tables\Columns\Column $column) {
                        $newStatus = $record->status % 3 + 1;
                        $record->update(['status' => $newStatus]);
                        Notification::make()
                            ->title('Status updated')
                            ->body('Changed to: ' . match($newStatus) {
                                    1 => __('common.Open'),
                                    2 => __('common.Closed'),
                                    3 => __('common.Delayed'),
                                })
                            ->success()
                            ->send();
                    })
                    ->tooltip(fn ($record): string => match((int) $record->status) {
                        1 => __('common.Open - Click to change'),
                        2 => __('common.Closed - Click to change'),
                        3 => __('common.Delayed - Click to change'),
                        default => __('Unknown status'),
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label(__('common.category')),

                Tables\Filters\SelectFilter::make('level')
                    ->relationship('level', 'name')
                    ->label(__('common.level')),

                Tables\Filters\Filter::make('active')
                    ->label(__('common.active_courses'))
                    ->query(fn (Builder $query) => $query->where('status', true)),
            ])
            ->actions([
               ActionGroup::make([
            
                   Tables\Actions\Action::make('students')
                       ->label(__('common.students'))
                       ->icon('heroicon-o-users')
                       ->url(fn($record) => static::getUrl('students', ['record' => $record])),

                   Tables\Actions\Action::make('materials')
                       ->label(__('common.materials'))
                       ->icon('heroicon-o-book-open')
                       ->url(fn($record) => static::getUrl('materials', ['record' => $record])),

                   Tables\Actions\Action::make('schedules')
                       ->label(__('common.schedules'))
                       ->icon('heroicon-o-calendar-days')
                       ->url(fn($record) => static::getUrl('schedules', ['record' => $record])),

                   Tables\Actions\Action::make('attendance')
                       ->label(__('common.attendance'))
                       ->icon('heroicon-o-clipboard-document-check')
                       ->url(fn($record) => static::getUrl('attendance', ['record' => $record])),

                   Tables\Actions\Action::make('payments')
                       ->label(__('common.payments'))
                       ->icon('heroicon-o-currency-dollar')
                       ->url(fn($record) => static::getUrl('payments', ['record' => $record])),

                   Tables\Actions\Action::make('tests')
                       ->label(__('common.tests'))
                       ->icon('heroicon-o-currency-dollar')
                       ->url(fn($record) => static::getUrl('tests', ['record' => $record])),

                   Tables\Actions\Action::make('assignments')
                       ->label(__('common.assignments'))
                       ->icon('heroicon-o-currency-dollar')
                       ->url(fn($record) => static::getUrl('assignments', ['record' => $record])),
                   Tables\Actions\Action::make('complaints')
                       ->label(__('common.assignments'))
                       ->icon('heroicon-o-currency-dollar')
                       ->url(fn($record) => static::getUrl('complaints', ['record' => $record])),
                       Tables\Actions\EditAction::make(),
                       Tables\Actions\DeleteAction::make(),
                ])
                   ->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('common.activate'))
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['status' => true])),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('common.deactivate'))
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['status' => false])),
                ]),
            ])
            ->defaultSort('start_date', 'desc');
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourses::route('/create'),
            'edit' => Pages\EditCourses::route('/{record}/edit'),
            'students' => Pages\CourseStudents::route('/{record}/students'),
            'materials' => Pages\Materials::route('/{record}/materials'),
            'schedules' => Pages\Schedules::route('/{record}/schedules'),
            'attendance' => Pages\Attendance::route('/{record}/attendance'),
            'payments' => Pages\CoursePayments::route('/{record}/payments'),
            'tests' => Pages\CourseTestsPage::route('/{record}/tests'),
            'assignments' => Pages\Assignments::route('/{record}/assignments'),
            'complaints' => Pages\Complaints::route('/{record}/complaints'),
            'groups' => Pages\Groups::route('/{record}/groups'),
           // 'tests/students' => Pages\ManageTestStudents::route('/{record}/tests'),
        ];
    }

    /********************************************************/
    protected static function calculateTotalPrice(Get $get, Set $set): void
    {
        $price = (float) ($get('price') ?? 0);
        $discountType = $get('discount_type');
        $discount = (float) ($get('discount') ?? 0);

        $total = match ($discountType) {
            'p' => $price - ($price * ($discount / 100)), // Percentage discount
            'v' => $price - $discount, // Fixed discount
            default => $price, // No discount
        };

        $set('total_price', max($total, 0)); // Ensure total is never negative
    }

    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================


    public static function getBreadCrumb(): string
    {
        return __('common.courses');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.courses');
    }

    public static function getLabel(): string
    {
        return __('common.courses');
    }

    public static function getModelLabel(): string
    {
        return __('common.course');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.courses');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.courses');
    }
}
