<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseGroups;
use App\Models\Students;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;

class CourseStudents extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.course-students';
    protected static ?string $title = null;
    public $tap = 'students';
    public $record, $course;
    public $student_id;
    public $group_id;
    public $course_type;
    public $payment_type;
    public $total_price = 0;
    public $duration = 0;
    public $installment_amount;
    public $number_of_installments = 1;
    public $initial_payment = 0;

    protected CourseService $courseService;
    protected StudentService $studentService;

    protected $listeners = ['refreshForm' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record;
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->course = $this->courseService->get_course($record);
        $this->total_price = $this->course->total_price ?? 0;
        $this->duration = $this->course->duration ?? 1;
        $this->number_of_installments = $this->course->duration ?? 1;
        $this->installment_amount = $this->total_price / $this->duration;

    }

    public function getTitle(): string
    {
        return __('common.CourseStudents');
    }

    public function saveStudents()
    {
        $this->validate([
            'student_id' => 'required',
            'group_id' => 'required',
            'course_type' => 'required',
            'payment_type' => 'required',
        ]);

        $data = [
            'course_id' => $this->record,
            'student_id' => $this->student_id,
            'group_id' => $this->group_id,
            'type' => $this->course_type,

        ];

        $payment_data = [
            'payment_type' => $this->payment_type,
            'course_id' => $this->record,
            'group_id' => $this->group_id,
            'start_date' => $this->course->start_date,
            'end_date' => $this->course->end_date,
            'student_id' => $this->student_id,
            'total_price' => $this->total_price ?? 0,
            'duration' => $this->duration ?? 0,
            'installment_amount' => $this->installment_amount ?? 0,
            'number_of_installments' => $this->number_of_installments ?? 0,
            'initial_payment' => $this->initial_payment ?? 0,
        ];

      //  dd($payment_data);
        try {
            $this->courseService = app(CourseService::class);
            $this->studentService = app(StudentService::class);
            $course_student = $this->courseService->save_students($data);
            $this->courseService->save_payment($course_student, $payment_data);

            Notification::make()
                ->title('Student saved successfully!')
                ->success()
                ->send();

            $this->reset([
                'student_id',
                'course_type',
                'payment_type',
                'total_price',
                'duration',
                'installment_amount',
                'number_of_installments',
                'initial_payment'
            ]);

            $this->dispatch('refresh');

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error saving student!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getTableQuery()
    {
        return \App\Models\CourseStudents::query()->where('course_id', $this->record);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('student.full_name')
                ->label(__('common.student')),
            TextColumn::make('student.code')
                ->label(__('common.code')),

            TextColumn::make('group.name')
                ->label(__('common.group')),
            TextColumn::make('type')
                ->label(__('common.type')),
            TextColumn::make('total_paid')
                ->label(__('common.total_paid'))
                ->getStateUsing(function ($record) {
                    $coursePayments = \App\Models\CoursePayments::where('course_id', $record->course_id)
                        ->where('student_id', $record->student_id)
                        ->get();
                    
                    $totalPaid = 0;
                    foreach ($coursePayments as $coursePayment) {
                        $totalPaid += \App\Models\PaymentTransactions::where('course_payment_id', $coursePayment->id)
                            ->whereIn('transaction_type', ['initial_payment', 'installment'])
                            ->sum('amount');
                    }
                    
                    return number_format($totalPaid, 2) . ' ' . __('common.currency');
                }),
            IconColumn::make('is_active')
                ->label('Status')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger')
                ->tooltip(fn($state): string => $state ? 'Active' : 'Inactive')
        ];
    }

    protected function getTableActions(): array
    {
        return [
            \Filament\Tables\Actions\Action::make('refund')
                ->label(__('common.refund_money'))
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading(__('common.confirm_refund'))
                ->modalDescription(__('common.refund_description'))
                ->modalSubmitActionLabel(__('common.confirm_refund_action'))
                ->modalCancelActionLabel(__('common.cancel'))
                ->action(function (\App\Models\CourseStudents $record) {
                    try {
                        // جعل الطالب غير نشط
                        $record->update(['is_active' => 0]);
                    
                        $deletedInstallments = \App\Models\CourseInstallments::where('course_id', $record->course_id)
                            ->where('student_id', $record->student_id)

                            ->get();
                        
                        $deletedInstallmentsCount = $deletedInstallments->count();
                        $deletedInstallmentsAmount = $deletedInstallments->sum('amount');
                        
                        \App\Models\CourseInstallments::where('course_id', $record->course_id)
                            ->where('student_id', $record->student_id)
                            ->delete();
                        
                        $coursePayments = \App\Models\CoursePayments::where('course_id', $record->course_id)
                            ->where('student_id', $record->student_id)
                            ->get();
                        
                        $totalRefundAmount = 0;
                        
                        foreach ($coursePayments as $coursePayment) {
                            $paidTransactions = \App\Models\PaymentTransactions::where('course_payment_id', $coursePayment->id)
                                ->whereIn('transaction_type', ['initial_payment', 'installment'])
                                ->get();
                            
                            foreach ($paidTransactions as $transaction) {
                                \App\Models\PaymentTransactions::create([
                                    'course_payment_id' => $coursePayment->id,
                                    'installment_id' => $transaction->installment_id,
                                    'course_id' => $record->course_id,
                                    'group_id' => $record->group_id,
                                    'student_id' => $record->student_id,
                                    'amount' => -$transaction->amount, // قيمة سالبة للاسترداد
                                    'payment_date' => now(),
                                    'payment_method_id' => $transaction->payment_method_id,
                                    'transaction_type' => 'refund',
                                ]);
                                
                                $totalRefundAmount += $transaction->amount;
                            }
                        }
                        
                        $notificationBody = __('common.refund_amount', ['amount' => number_format($totalRefundAmount, 2)]);
                        
                        if ($deletedInstallmentsCount > 0) {
                            $notificationBody .= "\n" . __('common.deleted_installments_info', [
                                'count' => $deletedInstallmentsCount,
                                'amount' => number_format($deletedInstallmentsAmount, 2)
                            ]);
                        }
                        
                        Notification::make()
                            ->title(__('common.refund_success'))
                            ->body($notificationBody)
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('common.refund_error'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn(\App\Models\CourseStudents $record) => $record->is_active == 1),
                
            \Filament\Tables\Actions\Action::make('reactivate')
                ->label(__('common.reactivate'))
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(__('common.confirm_reactivate'))
                ->modalDescription(__('common.reactivate_description'))
                ->modalSubmitActionLabel(__('common.confirm_reactivate_action'))
                ->modalCancelActionLabel(__('common.cancel'))
                ->action(function (\App\Models\CourseStudents $record) {
                    try {
                        $record->update(['is_active' => 1]);
                        
                        Notification::make()
                            ->title(__('common.reactivate_success'))
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('common.reactivate_error'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn(\App\Models\CourseStudents $record) => $record->is_active == 0),
                
            DeleteAction::make()
                ->label(__('common.delete'))
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->action(function (\App\Models\CourseStudents $record) {
                    $record->delete();
                    Notification::make()
                        ->title('Student removed from course')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('group_id')
                ->label(__('common.group'))
                ->options(
                    CourseGroups::where('course_id', $this->record)
                        ->where('status', 1)
                        ->pluck('name', 'id')
                )
                ->searchable(),

            SelectFilter::make('is_active')
                ->label(__('common.student_status'))
                ->options([
                    1 => __('common.active_student'),
                    0 => __('common.inactive_student'),
                ])
                ->default(1),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Grid::make(3)
                        ->schema([
                            Select::make('student_id')
                                ->label(__('common.student'))
                                ->options(Students::doesntHave('courses')->pluck('full_name', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('group_id')
                                ->label(__('common.group'))
                                ->options(CourseGroups::where('status',1)->where('course_id',$this->record)->pluck('name', 'id'))
                                ->searchable()
                                ->required(),


                            Select::make('course_type')
                                ->label(__('common.type'))
                                ->options([
                                    'online' => 'Online',
                                    'offline' => 'Offline',
                                    'hybrid' => 'Hybrid',
                                ])
                                ->required(),

                            Select::make('payment_type')
                                ->label(__('common.PaymentType'))
                                ->options([
                                    'cash' => __('common.Cash'),
                                    'installment' => __('common.Installment'),
                                    'payment_installment' => __('common.PaymentInstallment'),
                                ])
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn() => $this->dispatch('refreshForm')),
                        ]),

                    TextInput::make('total_price')
                        ->label(__('common.TotalPrice'))
                        ->numeric()
                        ->default($this->course->total_price)
                        ->required()
                        ->columnSpanFull(),


                    Grid::make(3)
                        ->schema(function (Get $get) {
                            $schema = [];
                            if ($get('payment_type') === 'cash') {

                            }
                            if ($get('payment_type') === 'installment') {
                                $schema = [
                                    TextInput::make('duration')
                                        ->label(__('common.NumberInstallments'))
                                        ->numeric()
                                        ->default(12)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state) {
                                            $this->duration = $state;
                                            $this->installment_amount = $this->total_price / $this->duration;
                                        }),

                                    TextInput::make('installment_amount')
                                        ->label(__('common.InstallmentAmount'))
                                        ->numeric()
                                        ->disabled()
                                        ->default(function (Get $get) {
                                            return $get('total_price') / $get('duration', 1);
                                        }),
                                ];
                            }

                            if ($get('payment_type') === 'payment_installment') {
                                $schema = [
                                    TextInput::make('initial_payment')
                                        ->label(__('common.InitialPayment'))
                                        ->numeric()
                                        ->default(0)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state) {
                                            $this->initial_payment = $state;
                                            $this->installment_amount = ($this->total_price - $this->initial_payment) / $this->number_of_installments;
                                        }),

                                    TextInput::make('number_of_installments')
                                        ->label(__('common.InstallmentAmount'))
                                        ->numeric()
                                        ->default(3)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state) {
                                            $this->number_of_installments = $state;
                                            $this->installment_amount = ($this->total_price - $this->initial_payment) / $this->number_of_installments;
                                        }),

                                    TextInput::make('installment_amount')
                                        ->label(__('common.InstallmentAmount'))
                                        ->numeric()
                                        ->disabled()
                                        ->default(function (Get $get) {
                                            $remaining = $get('total_price') - $get('initial_payment', 0);
                                            return $remaining / $get('number_of_installments', 1);
                                        }),
                                ];
                            }

                            return $schema;
                        }),

                    Actions::make([
                        Action::make('save')
                            ->label(__('common.Add'))
                            ->action('saveStudents')
                            ->color('primary')
                            ->icon('heroicon-o-plus')
                    ])
                        ->alignEnd()
                        ->extraAttributes(['class' => 'mt-6'])
                ])
        ];
    }

    protected function calculateInstallmentAmount()
    {
        if ($this->payment_type === 'payment_installment' &&
            $this->total_price &&
            $this->initial_payment &&
            $this->number_of_installments) {
            $remaining_amount = $this->total_price - $this->initial_payment;
            $this->installment_amount = $remaining_amount / $this->number_of_installments;
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['initial_payment', 'number_of_installments', 'total_price'])) {
            $this->calculateInstallmentAmount();
        }
    }
}
