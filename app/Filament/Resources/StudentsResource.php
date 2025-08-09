<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentsResource\Pages;
use App\Filament\Resources\StudentsResource\RelationManagers;
use App\Models\Students;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Country;
use App\Models\City;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;

class StudentsResource extends Resource
{
    protected static ?string $model = Students::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 18;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'full_name';
    public static function getGlobalSearchResultUrl($model): string
    {
        return StudentsResource::getUrl('details', ['record' => $model->id]);
    }

    
    public static function getGlobalSearchResultDetails($model): array
    {
        return [
            'Name' => $model->full_name,
            'Code' => $model->code,
        ];
    }
    // public static function getNavigationGroup(): string
    // {
    //     return __('common.general');
    // }
    //  protected static ?int $sort = 1;
    //    public static function getNavigationGroup(): string
    //    {
    //        return __('common.users_management');
    //    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('common.PersonalInformation'))
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label(__('common.FullName'))
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label(__('common.Email'))
                            ->email(),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('common.Phone'))
                            ->tel()
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->label(__('common.city_id'))
                            ->options(Country::whereNull('parent_id')->pluck('name', 'id'))
                            ->live()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('addCity')
                                    ->icon('heroicon-o-plus')
                                    ->form([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->label(__('common.Name')),
                                    ])
                                    ->action(function (array $data) {
                                        $country = Country::create([
                                            'name' => $data['name'],
                                            'parent_id' => null,
                                        ]);
                                    })
                            ),
                        Forms\Components\Select::make('region_id')
                            ->label(__('common.region_id'))
                            ->options(function (Get $get) {
                                $cityId = $get('city_id');
                                if (!$cityId) {
                                    return [];
                                }
                                return City::where('parent_id', $cityId)->pluck('name', 'id');
                            })
                            ->required()
                            ->preload()
                            ->searchable()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('addRegion')
                                    ->icon('heroicon-o-plus')
                                    ->form([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('parent_id')
                                                    ->options(
                                                        City::whereNull('parent_id')
                                                            ->pluck('name', 'id')
                                                    )
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->label(__('common.Country'))
                                                    ->native(false),
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->label(__('common.Name')),
                                            ]),
                                    ])
                                    ->action(function (array $data) {
                                        $record['name'] = $data['name'];
                                        $record['parent_id'] = $data['parent_id'];
                                        $region = City::create($record);
                                    })
                            ),
                        Forms\Components\TextInput::make('address')
                            ->label(__('common.Address'))
                            ->required(),
                        Forms\Components\Select::make('gender')
                            ->label(__('common.Gender'))
                            ->options([
                                'male' => __('common.Male'),
                                'female' => __('common.Female'),
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('age')
                            ->label(__('common.Age'))
                            ->numeric(),
                        SpatieMediaLibraryFileUpload::make('profile_image')
                            ->collection('students')
                            ->label(__('common.ProfileImage'))
                            ->image()
                            ->responsiveImages()
                            ->disk('public')
                            ->rules(['image', 'max:2048']),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('common.AcademicInformation'))
                    ->schema([
                        Forms\Components\TextInput::make('educational_qualification')
                            ->label(__('common.EducationalQualification'))
                            ->required(),
                        Forms\Components\TextInput::make('field_of_study')
                            ->label(__('common.FieldOfStudy'))
                            ->required(),
                        Forms\Components\TextInput::make('educational_institution')
                            ->label(__('common.EducationalInstitution'))
                            ->helperText(__('common.EducationalInstitutionHelp'))
                            ->required(),
                        SpatieMediaLibraryFileUpload::make('cv')
                            ->collection('students_cv')
                            ->label(__('common.CV'))
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->rules(['file', 'max:5120']),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('common.AdministrativeInformation'))
                    ->schema([
                        Forms\Components\DatePicker::make('registration_date')
                            ->label(__('common.RegistrationDate'))
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label(__('common.Status'))
                            ->options([
                                1 => __('common.Active'),
                                0 => __('common.Inactive'),
                            ])
                            ->default(1)
                            ->required(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label(__('common.AdminNotes'))
                            ->helperText(__('common.AdminNotesHelp')),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('common.PersonalNotes'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('id_proof')
                            ->collection('students_id')
                            ->label(__('common.IDProof'))
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->disk('public')
                            ->rules(['file', 'max:2048']),
                        Forms\Components\Textarea::make('notes')
                            ->label(__('common.PersonalNotes'))
                            ->helperText(__('common.PersonalNotesHelp')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('common.FullName'))
                    ->formatStateUsing(fn($state, $record) => $state . ' (' . $record->code . ')')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('common.Email'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('common.Phone'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('age')
                    ->label(__('common.Age'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('common.Address'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('registration_date')
                    ->label(__('common.RegistrationDate'))
                    ->date()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label(__('common.Status'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('common.Notes'))
                    ->limit(50),

            ])
            ->filters([
                Tables\Filters\Filter::make('full_name_or_code')
                    ->label(__('common.FullName') . ' / كود الطالب')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label(__('common.FullName') . ' أو كود الطالب'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'], function ($q, $value) {
                            $q->where('full_name', 'like', "%$value%")
                                ->orWhere('code', 'like', "%$value%");
                        });
                    }),
                Tables\Filters\SelectFilter::make('gender')
                    ->label(__('common.Gender'))
                    ->options([
                        'male' => __('common.Male'),
                        'female' => __('common.Female'),
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('common.Status'))
                    ->options([
                        1 => __('common.Active'),
                        0 => __('common.Inactive'),
                    ]),
                Tables\Filters\SelectFilter::make('city_id')
                    ->label(__('common.city_id'))
                    ->relationship('city', 'name')
                    ->preload()
                    ->searchable(),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                \Filament\Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('details')
                        ->label(__('common.Details'))
                        ->icon('heroicon-o-document-text')
                        ->url(fn($record) => static::getUrl('details', ['record' => $record])),

                    Tables\Actions\Action::make('courses')
                        ->label(__('common.courses'))
                        ->icon('heroicon-o-book-open')
                        ->url(fn($record) => static::getUrl('courses', ['record' => $record])),

                    Tables\Actions\Action::make('schedules')
                        ->label(__('common.schedules'))
                        ->icon('heroicon-o-calendar')
                        ->url(fn($record) => static::getUrl('schedules', ['record' => $record])),

                    Tables\Actions\Action::make('attendance')
                        ->label(__('common.attendance'))
                        ->icon('heroicon-o-user-group')
                        ->url(fn($record) => static::getUrl('attendance', ['record' => $record])),

                    Tables\Actions\Action::make('payments')
                        ->label(__('common.payments'))
                        ->icon('heroicon-o-currency-dollar')
                        ->url(fn($record) => static::getUrl('payments', ['record' => $record])),

                    Tables\Actions\Action::make('tests')
                        ->label(__('common.tests'))
                        ->icon('heroicon-o-clipboard-document-check')
                        ->url(fn($record) => static::getUrl('tests', ['record' => $record])),

                    Tables\Actions\Action::make('assignments')
                        ->label(__('common.assignments'))
                        ->icon('heroicon-o-document')
                        ->url(fn($record) => static::getUrl('assignments', ['record' => $record])),
                ])
                    ->icon('heroicon-m-ellipsis-vertical'),
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
            'create' => Pages\CreateStudents::route('/create'),
            'index' => Pages\ListStudents::route('/'),
            'edit' => Pages\EditStudents::route('/{record}/edit'),
            'details' => Pages\StudentDetails::route('/{record}/details'),
            'courses' => Pages\Courses::route('/{record}/courses'),
            'schedules' => Pages\Schedules::route('/{record}/schedules'),
            'attendance' => Pages\Attendance::route('/{record}/attendance'),
            'payments' => Pages\Payments::route('/{record}/payments'),
            'tests' => Pages\Tests::route('/{record}/tests'),
            'assignments' => Pages\Assigments::route('/{record}/assignments'),
        ];
    }



    public static function getBreadCrumb(): string
    {
        return __('common.students');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.students');
    }

    public static function getLabel(): string
    {
        return __('common.students');
    }

    public static function getModelLabel(): string
    {
        return __('common.student');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.students');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.students');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['full_name', 'code']; // Add 'code' here
    }
}
