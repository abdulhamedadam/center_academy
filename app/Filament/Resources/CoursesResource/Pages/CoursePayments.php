<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseAttendance;
use App\Models\PaymentTransactions;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class CoursePayments extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.course-payments';
    public $tap = 'payments';
    public $sub_tap = 'payments';
    public $record, $course;
    protected CourseService $courseService;
    protected StudentService $studentService;
    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
       // dd($this->record);
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->course = $this->courseService->get_course($record);
    }

    public function table(Table $table): Table
    {
        if ($this->sub_tap === 'payments_table') {
            return $this->paymentsDetailsTable($table);
        } elseif ($this->sub_tap === 'installments') {
            return $this->installmentsTable($table);
        }

        return $this->paymentsTable($table);
    }

    protected function paymentsTable(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\CoursePayments::query()
                    ->where('course_id', $this->record)
                    ->with(['student'])
            )
            ->columns([
                TextColumn::make('student.full_name')
                    ->label(__('common.StudentName'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label(__('common.TotalAmount'))
                    ->sortable(),

                TextColumn::make('payment_transactions.amount')
                    ->label(__('common.PaidAmount'))
                    ->sortable(),

                TextColumn::make('remaining_amount')
                    ->label(__('common.RemainingAmount'))
                    ->getStateUsing(function ($record) {
                        return $record->total_amount - $record->payment_transactions->sum('amount');
                    }),

                TextColumn::make('payment_type')
                    ->label(__('common.PaymentMethod'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'success',
                        'installment' => 'primary',
                        'payment_installment' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label(__('common.PaymentStatus'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'remaining' => 'warning',
                        'late' => 'info',
                        default => 'danger',
                    }),

                TextColumn::make('created_at')
                    ->label(__('common.PaymentDate'))
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                Action::make('printInvoice')
                    ->label('طباعة فاتورة')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('payments.print-invoice', $record->id))
                    ->openUrlInNewTab(),
              //  DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function installmentsTable(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\CourseInstallments::query()
                    ->whereHas('coursePayment', function($query) {
                        $query->where('course_id', $this->record);
                    })
                    ->with(['coursePayment.student'])
            )
            ->columns([
                TextColumn::make('coursePayment.student.full_name')
                    ->label(__('common.StudentName'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label(__('common.Amount'))
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label(__('common.DueDate'))
                    ->date()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('common.Status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'overdue' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('payment_transaction.payment_date')
                    ->label(__('common.PaidDate'))
                    ->date()
                    ->placeholder('Not paid yet')
                    ->sortable(),
            ])
            ->actions([

                EditAction::make()
                    ->form([
                        DatePicker::make('due_date')
                            ->label(__('common.DueDate'))
                            ->required(),

                        TextInput::make('amount')
                            ->label(__('common.Amount'))
                            ->numeric()
                            ->required(),
                    ]),

                Action::make('markAsPaid')
                    ->label(__('common.pay'))
                    ->icon('heroicon-o-banknotes')
                    ->form([
                        DatePicker::make('paid_date')
                            ->label(__('common.PaymentDate'))
                            ->default(now())
                            ->required(),

                        TextInput::make('amount_paid')
                            ->label(__('common.AmountPaid'))
                            ->numeric()
                            ->default(fn($record) => $record->amount)
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {

                        $record->update([
                            'status' => 'paid',
                           // 'paid_date' => $data['paid_date'],
                        ]);

                        \App\Models\PaymentTransactions::create([
                            'course_payment_id' => $record->course_payment_id,
                            'installment_id' => $record->id,
                            'amount' => $data['amount_paid'],
                            'payment_date' => $data['paid_date'],
                            'payment_method_id' => 1,
                            'transaction_type' => 'installment',
                        ]);

                        $coursePayment = $record->coursePayment;
                        $totalPaid = $coursePayment->payment_transactions->sum('amount');
                        if ($totalPaid >= $coursePayment->total_amount) {
                            $coursePayment->update(['status' => 'paid']);
                        }

                        Notification::make()
                            ->title(__('Installment marked as paid successfully'))
                            ->success()
                            ->send();
                    })
                    ->visible(fn($record) => $record->status !== 'paid'),
            ])
            
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function paymentsDetailsTable(Table $table): Table
    {
        return $table
            ->query(
                PaymentTransactions::query()
                    ->whereHas('coursePayment', function($query) {
                        $query->where('course_id', $this->record);
                    })
                    ->with(['coursePayment.student'])
            )
            ->columns([
                TextColumn::make('coursePayment.student.full_name')
                    ->label(__('common.StudentName'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label(__('common.Amount'))
                    ->sortable(),


                TextColumn::make('payment_date')
                    ->label(__('common.PaymentDate'))
                    ->date()
                    ->sortable(),



            ])
            ->actions([
                Action::make('printInvoice')
                    ->label('طباعة فاتورة')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('payments.print-invoice', $record->id))
                    ->openUrlInNewTab(),
              //  DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
