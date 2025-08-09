<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstallmentPaymentResource\Pages;
use App\Models\CourseInstallments;
use App\Models\PaymentTransactions;
use App\Models\Students;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InstallmentPaymentResource extends Resource
{
    protected static ?string $model = CourseInstallments::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 7;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationGroup(): string
    {
        return __('common.Financial');
    }
    //------------------------------------------------------------------------------------------------------------------
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label(__('common.Student'))
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $set('course_id', null);
                        $set('installment_id', null);
                    })
                    ->options(Students::query()->pluck('full_name', 'id')) // Direct query
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('course_id')
                    ->label(__('common.Course'))
                    ->live()
                    ->afterStateUpdated(fn ($set) => $set('installment_id', null))
                    ->options(function (Forms\Get $get) {
                        if (!$get('student_id')) {
                            return [];
                        }
                        return DB::table('tbl_course_students')
                        ->where('student_id', $get('student_id'))
                            ->join('tbl_courses', 'tbl_course_students.course_id', '=', 'tbl_courses.id')
                            ->pluck('tbl_courses.name', 'tbl_courses.id');
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('installment_id')
                    ->label(__('common.Installment'))
                    ->live()
                    ->options(function (Forms\Get $get) {
                        if (!$get('course_id')) {
                            return [];
                        }
                        return DB::table('tbl_course_installments')
                            ->where('course_id', $get('course_id'))
                            ->where('status', '!=', 'paid')
                            ->orderBy('due_date')
                            ->get()
                            ->mapWithKeys(fn ($item) => [
                                $item->id => Carbon::parse($item->due_date)->format('Y-m-d') . ' - (' . number_format($item->amount, 2).')'
                            ]);
                    })
                    ->afterStateUpdated(function ($state, Forms\Set $set) {

                        $installment = DB::table('tbl_course_installments')->find($state);
                        if ($installment) {
                            $set('amount', $installment->amount);
                        }
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label(__('common.Amount'))
                    ->numeric()
                    ->required()
                    ->default(function (Forms\Get $get) {
                        if ($get('installment_id')) {
                            return DB::table('tbl_course_installments')
                                ->where('id', $get('installment_id'))
                                ->value('amount');
                        }
                        return null;
                    }),

            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->query(CourseInstallments::with(['student', 'course', 'group']))
            ->columns([
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label(__('common.Student'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.name')
                    ->label(__('common.Course'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('group.name')
                    ->label(__('common.Group'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('common.Amount'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('common.PaymentDate'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('common.Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => __("common.{$state}")),
            ])

            ->filters([
                Filter::make('status')
                    ->form([])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            $query->where('status', $data['value']);
                        }
                    })
                    ->indicateUsing(function (array $data) {
                        if (!isset($data['value'])) {
                            return null;
                        }
                        return $data['value'] === 'paid'
                            ? __('common.PaidPayments')
                            : __('common.UnpaidPayments');
                    }),

            ])
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\Action::make('mark_unpaid')
                    ->label(__('common.MarkAsUnpaid'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Model $record) {
                        $record->update(['status' => 'remaining']);
                        $record->payment_transaction()->delete();
                        Notification::make()
                            ->title(__('Payment marked as unpaid'))
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Model $record) => $record->status === 'paid'),

                    Tables\Actions\Action::make('printInvoice')
                    ->label('طباعة فاتورة')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('payments.print-invoice', $record->payment_transaction?->id))
                    ->openUrlInNewTab()
                    ->visible(fn (Model $record) => $record->status === 'paid'),
                Tables\Actions\Action::make('mark_paid')
                    ->label(__('common.MarkAsPaid'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Model $record) {
                        $record->update(['status' => 'paid']);
                        
                        $transaction = PaymentTransactions::create([
                            'installment_id' => $record->id,
                            'course_payment_id' => $record->course_payment_id,
                            'amount' => $record->amount,
                            'payment_date' => now(),
                            'transaction_type' => 'installment',
                            'student_id' => $record->student_id,
                            'course_id' => $record->course_id,
                            'group_id' => $record->group_id,
                        ]);
                        
                        Notification::make()
                            ->title(__('Payment marked as paid'))
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Model $record) => $record->status !== 'paid'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_paid')
                        ->label(__('common.MarkSelectedAsPaid'))
                        ->icon('heroicon-o-check-circle')
                        ->action(function (Collection $records) {
                           
                            $records->each->update(['status' => 'paid']);
                            
                            $transactions = $records->map(fn ($record) => [
                                'installment_id' => $record->id,
                                'course_payment_id' => $record->course_payment_id,
                                'amount' => $record->amount,
                                'payment_date' => now(),
                                'transaction_type' => 'installment',
                                'student_id' => $record->student_id,
                                'course_id' => $record->course_id,
                                'group_id' => $record->group_id,
                            ])->toArray();
                            
                            PaymentTransactions::insert($transactions);
                            
                            Notification::make()
                                ->title(__('Payments marked as paid'))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('mark_unpaid')
                        ->label(__('common.MarkSelectedAsUnpaid'))
                        ->icon('heroicon-o-x-circle')
                        ->action(function (Collection $records) {
                            $records->each->update(['status' => 'remaining']);
                            PaymentTransactions::whereIn('course_payment_id', $records->pluck('id'))->delete();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstallmentPayments::route('/'),
           // 'create' => Pages\CreateInstallmentPayment::route('/create'),
           // 'edit' => Pages\EditInstallmentPayment::route('/{record}/edit'),
        ];
    }

    //------------------------------------------------------------------------------------------------------------------
    public static function getBreadCrumb(): string
    {
        return __('common.InstallmentPayments');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.InstallmentPayments');
    }

    public static function getLabel(): string
    {
        return __('common.InstallmentPayments');
    }

    public static function getModelLabel(): string
    {
        return __('common.InstallmentPayment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.InstallmentPayments');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.InstallmentPayments');
    }
}
