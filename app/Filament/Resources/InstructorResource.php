<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Filament\Resources\InstructorResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\Instructor;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 16;
    // public static function getNavigationGroup(): string
    // {
    //     return __('common.general');
    // }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $recordTitleAttribute = 'name';
    public static function getGlobalSearchResultUrl($model): string
    {
        return InstructorResource::getUrl('details', ['record' => $model->id]);
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
//    protected static ?int $sort = 3;
//    public static function getNavigationGroup(): string
//    {
//        return __('common.users_management');
//    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('common.basic_info'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('common.name'))
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label(__('common.email'))
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('common.phone'))
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
                        Forms\Components\TextInput::make('address1')
                            ->label(__('common.address1'))
                            ,
                        Forms\Components\Select::make('gender')
                            ->label(__('common.gender'))
                            ->options([
                                1 => __('common.male'),
                               2=> __('common.female'),
                            ]),
                        Forms\Components\TextInput::make('age')
                            ->label(__('common.Age'))
                            ->numeric()
                            ,
                        Forms\Components\FileUpload::make('image')
                            ->label(__('common.image'))
                            ->image()
                            ->directory('instructors')
                            ,
                    ])->columns(3),

                Forms\Components\Section::make(__('common.specialization_and_qualifications'))
                    ->schema([
                        Forms\Components\TextInput::make('specialization')
                            ->label(__('common.specialization'))
                            ->required(),
                        Forms\Components\TextInput::make('qualifications')
                            ->label(__('common.qualifications'))
                            ->required(),
                        Forms\Components\TextInput::make('experience')
                            ->label(__('common.experience'))
                            ->numeric()
                            ->required(),
                        Forms\Components\Textarea::make('bio')
                            ->label(__('common.bio'))
                            ->required(),
                        Forms\Components\FileUpload::make('cv')
                            ->label(__('common.cv'))
                            ->directory('instructors/cv')
                            ->acceptedFileTypes(['application/pdf'])
                            ,
                    ])->columns(3),

                Forms\Components\Section::make(__('common.administrative_data'))
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(__('common.status'))
                            ->options([
                                1 => __('common.active'),
                                0 => __('common.inactive'),
                            ])
                            ->default(1)
                            ->required(),
                    
                        Forms\Components\DatePicker::make('hire_date')
                            ->label(__('common.hire_date'))
                            ->required(),
                        Forms\Components\Textarea::make('administrative_notes')
                            ->label(__('common.administrative_notes'))
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->formatStateUsing(fn($state, $record) => $state . ' (' . $record->code . ')')
                    ->label(__('common.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('common.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('common.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('specialization')
                    ->label(__('common.specialization'))
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label(__('common.status')),

            ])
            ->filters([
                Tables\Filters\Filter::make('full_name_or_code')
                ->label(__('common.name'))
                ->form([
                    \Filament\Forms\Components\TextInput::make('value')
                        ->label(__('common.name') ),
                ])
                ->query(function ($query, array $data) {
                    return $query->when($data['value'], function ($q, $value) {
                        $q->where('name', 'like', "%$value%")
                            ->orWhere('code', 'like', "%$value%");
                    });
                }),
                Tables\Filters\SelectFilter::make('gender')
                    ->label(__('common.gender'))
                    ->options([
                        1 => __('common.male'),
                        2 => __('common.female'),
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('common.status'))
                    ->options([
                        1 => __('common.active'),
                        0 => __('common.inactive'),
                    ]),
                Tables\Filters\SelectFilter::make('specialization')
                    ->label(__('common.specialization'))
                    ->options(
                        \App\Models\Instructor::query()
                            ->distinct()
                            ->pluck('specialization', 'specialization')
                            ->filter()
                            ->toArray()
                    ),
              
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('details')
                    ->label(__('common.Details'))
                    ->icon('heroicon-o-document-text')
                    ->url(fn($record) => static::getUrl('details', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
            'details' => Pages\InstructorDetails::route('/{record}/details'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================


    public static function getBreadCrumb(): string
    {
        return __('common.instructors');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.instructors');
    }

    public static function getLabel(): string
    {
        return __('common.instructors');
    }

    public static function getModelLabel(): string
    {
        return __('common.instructor');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.instructors');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.instructors');
    }
}
