<?php

namespace App\Filament\Pages;

use App\Models\Course;
use App\Models\Courses;
use App\Models\Student;
use App\Models\CoursePayments;
use App\Models\Students;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class PaymentsReceivedReport extends Page
{
    protected static string $view = 'filament.pages.payments-received-report';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 11;
    public static function getNavigationGroup(): string
    {
        return __('common.Financial');
    }
    //protected static ?string $navigationGroup = 'Reports';
    //protected static ?int $navigationSort = 2;
    //------------------------------------------------------------------------------------------------------------------
    public ?array $data = [];
    public array $reportData = [];
    public bool $showReport = false;

    //------------------------------------------------------------------------------------------------------------------
    public static function getNavigationLabel(): string
    {
        return __('common.PaymentsReceived');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function getTitle(): string
    {
        return __('common.PaymentsReceived');
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
                Section::make(__('common.ReportFilters'))
                    ->schema([
                        DatePicker::make('from_date')
                            ->label(__('common.FromDate'))
                            ->required(),

                        DatePicker::make('to_date')
                            ->label(__('common.ToDate'))
                            ->required(),

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

                        \Filament\Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('generate')
                                ->label(__('common.GenerateReport'))
                                ->button()
                                ->action('generateReport')
                        ]),
                    ])
                    ->columns(4)
            ])
            ->statePath('data');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function generateReport()
    {
        // $this->validate();
        $this->showReport = true;
        $this->reportData = $this->prepareReportData();
    }

    //------------------------------------------------------------------------------------------------------------------
    protected function prepareReportData(): array
    {
        $query = CoursePayments::with(['payment_transactions', 'course', 'student']);

        // Apply date filters only if they're provided
        if (!empty($this->data['from_date']) && !empty($this->data['to_date'])) {
            $fromDate = Carbon::parse($this->data['from_date']);
            $toDate = Carbon::parse($this->data['to_date'])->endOfDay();
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        if (!empty($this->data['course_id'])) {
            $query->where('course_id', $this->data['course_id']);
        }

        if (!empty($this->data['student_id'])) {
            $query->where('student_id', $this->data['student_id']);
        }

        return $query->get()
            ->map(function ($payment) {
                return [
                    'date' => isset($payment->payment_transactions->first()->payment_date)
                        ? Carbon::parse($payment->payment_transactions->first()->payment_date)->format('Y-m-d')
                        : null,
                    'reference' => @$payment->payment_transactions->first()->id,
                    'student' => @$payment->student->full_name ?? 'N/A',
                    'course' => @$payment->course->name ?? 'N/A',
                    'amount' => @$payment->total_amount,
                    'paid' => @$payment->payment_transactions->sum('amount'),
                    'method' => @$payment->payment_type,
                    'status' => @$payment->status,
                ];
            })
            ->toArray();
    }
    //------------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
}
