<?php

namespace App\Filament\Pages;


use App\Models\CourseInstallments;
use App\Models\Courses;

use App\Models\Students;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;

class OverdueInstallmentsReport extends Page
{
    protected static string $view = 'filament.pages.overdue-installments-report';
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?int $navigationSort = 10;
    public static function getNavigationGroup(): string
    {
        return __('common.Financial');
    }
  //  protected static ?string $navigationGroup = 'Reports';
  //  protected static ?int $navigationSort = 3;

    public ?array $data = [];
    public array $reportData = [];
    public bool $showReport = false;
    //------------------------------------------------------------------------------------------------------------------
    public static function getNavigationLabel(): string
    {
        return __('common.OverdueInstallmentsReport');
    }
    //------------------------------------------------------------------------------------------------------------------
    public function getTitle(): string
    {
        return __('common.OverdueInstallmentsReport');
    }
    //------------------------------------------------------------------------------------------------------------------
//    public static function getNavigationGroup(): string
//    {
//        return __('common.Reports');
//    }
    //------------------------------------------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->form->fill();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('common.OverdueInstallmentsReport'))
                    ->schema([
                        Select::make('course_id')
                            ->label(__('common.Course'))
                            ->options(Courses::pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Select::make('student_id')
                            ->label(__('common.Student'))
                            ->options(Students::pluck('full_name', 'id'))
                            ->searchable()
                            ->nullable(),

                        DatePicker::make('due_date')
                            ->label(__('common.DueBeforeDate'))
                            ->required(),
                    ])
                    ->columns(3)
            ])
            ->statePath('data');
    }
    //------------------------------------------------------------------------------------------------------------------
    public function generateReport()
    {
        $this->showReport = true;
        $this->reportData = $this->prepareReportData();
    }
    //------------------------------------------------------------------------------------------------------------------
    protected function prepareReportData(): array
    {
        $query = CourseInstallments::with([
            'coursePayment.course',
            'coursePayment.student',
            'payment_transaction'
        ])
            ->where('status', '!=', 'paid');

        if (!empty($this->data['due_date'])) {
            $query->where('due_date', '<=', Carbon::parse($this->data['due_date']));
        }

        if (!empty($this->data['course_id'])) {
            $query->whereHas('coursePayment', function($q) {
                $q->where('course_id', $this->data['course_id']);
            });
        }

        if (!empty($this->data['student_id'])) {
            $query->whereHas('coursePayment', function($q) {
                $q->where('student_id', $this->data['student_id']);
            });
        }

        return $query->get()
            ->map(function ($installment) {
                $dueDate = Carbon::parse($installment->due_date);
                $daysOverdue = (int) max(0, now()->diffInDays($dueDate, false));
                $paidAmount = $installment->payment_transaction
                    ? $installment->payment_transaction->sum('amount')
                    : 0;
                $remainingAmount = $installment->amount - $paidAmount;

                $status = $installment->status;
                if ($paidAmount >= $installment->amount) {
                    $status = 'paid';
                } elseif ($paidAmount > 0) {
                    $status = 'partial';
                }

                $contact1 = optional($installment->coursePayment)->student->phone ;
                $contact2 = optional($installment->coursePayment)->student->email ;
                   ;

                return [
                    'id' => $installment->id,
                    'student' => optional($installment->coursePayment)->student->full_name ?? 'N/A',
                    'course' => optional($installment->coursePayment)->course->name ?? 'N/A',
                    'reference' => optional($installment->coursePayment)->payment_reference ?? 'N/A',
                    'due_date' => $dueDate->format('Y-m-d'),
                    'days_overdue' => $daysOverdue,
                    'amount' => $installment->amount,
                    'paid' => $paidAmount,
                    'remaining' => $remainingAmount,
                    'status' => $status,
                    'contact1' => $contact1,
                    'contact2' => $contact2,
                    'contact_type1' => isset($installment->coursePayment->student->phone) ? 'phone' : 'email',
                    'contact_type2' => isset($installment->coursePayment->student->email) ? 'phone' : 'email',
                ];
            })
            ->sortByDesc('days_overdue')
            ->values()
            ->toArray();
    }
    //------------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
}
