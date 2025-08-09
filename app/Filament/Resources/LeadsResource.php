<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadsResource\Pages;
use App\Filament\Resources\LeadsResource\RelationManagers;
use App\Models\CrmLeads;
use App\Models\Leads;
use App\Models\Courses;
use App\Models\User;
use App\Models\Students;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Notifications;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class LeadsResource extends Resource
{
    protected static ?string $model = CrmLeads::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?int $navigationSort = 13;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationGroup(): ?string
    {
        return __('common.crm');
    }
    public static function getGlobalSearchResultDetails($model): array
    {
        return [
            'Course' => $model->course->name ?? 'No course',
            'Status' => $model->status,
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label(__('common.name')),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(20)
                                    ->label(__('common.phone')),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->label(__('common.email')),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('course_id')
                                    ->label(__('common.course'))
                                    ->options(Courses::pluck('name', 'id'))
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('source')
                                    ->label(__('common.source'))
                                    ->options(CrmLeads::getSourceLabels())
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->label(__('common.status'))
                                    ->options(CrmLeads::getStatusLabels())
                                    ->default(CrmLeads::STATUS_NEW)
                                    ->required(),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('assigned_to')
                                    ->label(__('common.AssignedTo'))
                                    ->options(User::pluck('name', 'id'))
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\DatePicker::make('first_contact_date')
                                    ->label(__('common.first_contact_date'))
                                    ->required(),
                            ]),

                        Forms\Components\Textarea::make('note')
                            ->label(__('common.notes'))
                            ->maxLength(65535)
                            ->columnSpan('full'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('common.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('common.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->label(__('common.course'))
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('common.status'))
                    ->colors([
                        'primary' => CrmLeads::STATUS_NEW,
                        'warning' => CrmLeads::STATUS_CONTACTED,
                        'info' => CrmLeads::STATUS_NEEDS_FOLLOWUP,
                        'success' => CrmLeads::STATUS_REGISTERED,
                        'danger' => CrmLeads::STATUS_NOT_INTERESTED,
                    ])
                    ->formatStateUsing(fn ($state) => CrmLeads::getStatusLabels()[$state] ?? __('common.unknown'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->label(__('common.source'))
                    ->formatStateUsing(fn ($state) => CrmLeads::getSourceLabels()[$state] ?? __('common.unknown'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label(__('common.AssignedTo'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_contact_date')
                    ->label(__('common.first_contact_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        CrmLeads::NEW => 'New',
                        CrmLeads::CONTACTED => 'Contacted',
                        CrmLeads::CONVERTED => 'Converted',
                        CrmLeads::NOTINTERSTED => 'Not Interested',
                        CrmLeads::LOST => 'Lost',
                    ]),
                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        'website' => 'Website',
                        'social' => 'Social Media',
                        'referral' => 'Referral',
                        'advertisement' => 'Advertisement',
                        'other' => 'Other',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label(__('common.view'))
                        ->icon('heroicon-o-eye'),
                    
                    Tables\Actions\EditAction::make()
                        ->label(__('common.edit'))
                        ->icon('heroicon-o-pencil'),
                    
                    Tables\Actions\Action::make('convert_to_student')
                        ->label(__('common.convert_to_student'))
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading(__('common.convert_to_student'))
                        ->modalDescription(__('common.convert_to_student_description'))
                        ->modalSubmitActionLabel(__('common.convert'))
                        ->action(function (CrmLeads $record) {
                            // إنشاء طالب جديد من البيانات
                            $student = Students::create([
                                'full_name' => $record->name,
                                'email' => $record->email,
                                'phone' => $record->phone,
                                'status' => 1,
                                'registration_date' => now(),
                                'notes' => 'تم التحويل من Lead: ' . $record->id . ' - ' . $record->note,
                            ]);

                            // ربط الطالب بالدورة إذا كان موجودة
                            if ($record->course_id) {
                                $student->courses()->attach($record->course_id);
                            }

                            // تحديث حالة الـ Lead إلى "محول"
                            $record->update([
                                'status' => CrmLeads::STATUS_REGISTERED,
                                'note' => $record->note . "\nتم التحويل إلى طالب في: " . now() . " (ID: " . $student->id . ")"
                            ]);

                            // إظهار رسالة نجاح
                            Notifications\Notification::make()
                                ->title(__('common.conversion_successful'))
                                ->body(__('common.student_created_successfully', ['id' => $student->id]))
                                ->success()
                                ->send();
                        })
                        ->visible(fn (CrmLeads $record) => $record->status != CrmLeads::STATUS_REGISTERED),
                    
                    Tables\Actions\DeleteAction::make()
                        ->label(__('common.delete'))
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip(__('common.actions')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('convert_multiple_to_students')
                        ->label(__('common.convert_multiple_to_students'))
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading(__('common.convert_multiple_to_students'))
                        ->modalDescription(__('common.convert_multiple_to_students_description'))
                        ->modalSubmitActionLabel(__('common.convert'))
                        ->action(function (Collection $records) {
                            $convertedCount = 0;
                            
                            foreach ($records as $record) {
                                // التحقق من أن الـ Lead لم يتم تحويله من قبل
                                if ($record->status != CrmLeads::STATUS_REGISTERED) {
                                    // إنشاء طالب جديد من البيانات
                                    $student = Students::create([
                                        'full_name' => $record->name,
                                        'email' => $record->email,
                                        'phone' => $record->phone,
                                        'status' => 1,
                                        'registration_date' => now(),
                                        'notes' => 'تم التحويل من Lead: ' . $record->id . ' - ' . $record->note,
                                    ]);

                                    // ربط الطالب بالدورة إذا كان موجودة
                                    if ($record->course_id) {
                                        $student->courses()->attach($record->course_id);
                                    }

                                    // تحديث حالة الـ Lead إلى "محول"
                                    $record->update([
                                        'status' => CrmLeads::STATUS_REGISTERED,
                                        'note' => $record->note . "\nتم التحويل إلى طالب في: " . now() . " (ID: " . $student->id . ")"
                                    ]);

                                    $convertedCount++;
                                }
                            }

                            // إظهار رسالة نجاح
                            Notifications\Notification::make()
                                ->title(__('common.bulk_conversion_successful'))
                                ->body(__('common.students_created_successfully', ['count' => $convertedCount]))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
          //  RelationManagers\FollowUpsRelationManager::class,
         //   RelationManagers\NotesRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLeads::route('/'),
        ];
    }


    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================


    public static function getBreadCrumb(): string
    {
        return __('common.leads');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.leads');
    }

    public static function getLabel(): string
    {
        return __('common.leads');
    }

    public static function getModelLabel(): string
    {
        return __('common.lead');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.leads');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.leads');
    }
}
