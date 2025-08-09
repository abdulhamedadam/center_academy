<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CoursesResource\Pages\CoursePayments;
use App\Filament\Resources\CoursesResource\Pages\CourseStudents;
use App\Filament\Widgets\RevenueChart;
use App\Models\Courses;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class RevenueReports extends Page
{
    protected static string $view = 'filament.pages.revenue-reports';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 9;
    public static function getNavigationGroup(): string
    {
        return __('common.Financial');
    }
    //protected static ?int $navigationSort = 1;
    public static function getNavigationLabel(): string
    {
        return __('common.Revenue Reports');
    }

    public function getTitle(): string
    {
        return __('common.Revenue Analytics');
    }
//    public static function getNavigationGroup(): string
//    {
//        return __('common.Reports');
//    }

    public ?array $data = [];
    public array $reportData = [];
    public bool $showReport = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('common.ReportFilters'))
                    ->schema([
                        DatePicker::make('start_date')
                            ->label(__('common.FromDate')),

                        DatePicker::make('end_date')
                            ->label(__('common.ToDate')),

                        Select::make('report_type')
                            ->label(__('common.ReportType'))
                            ->options([
                                'monthly' => __('common.MonthlySummary'),
                                'courses' => __('common.CoursesRevenue'),
                            ])
                            ->default('monthly'),

                        Select::make('course_id')
                            ->label(__('common.Course'))
                            ->options(Courses::pluck('name', 'id'))
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

    public function generateReport()
    {
        $this->validate();
        $this->showReport = true;
        $this->reportData = $this->prepareReportData();
        $this->dispatch('report-generated', [
            'reportData' => $this->reportData,
            'reportType' => $this->data['report_type'] ?? 'monthly'
        ]);
    }

    protected function prepareReportData(): array
    {
        $startDate = $this->data['start_date'] ?? now()->subYear();
        $endDate = $this->data['end_date'] ?? now();

        if ($this->data['report_type'] === 'monthly') {
            return $this->getMonthlyReport($startDate, $endDate);
        }

        return $this->getCoursesReport($startDate, $endDate);
    }

    protected function getMonthlyReport($startDate, $endDate): array
    {
        $report = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $previousData = null;

        while ($current <= $end) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();

            // Get all payments created in this month
            $payments = \App\Models\CoursePayments::whereBetween('created_at', [$monthStart, $monthEnd])
                ->with(['payment_transactions', 'installments'])
                ->get();

            // Calculate financials
            $totalRevenue = $payments->sum('total_amount');
            $paidAmount = $payments->sum(function($payment) {
                return $payment->payment_transactions->sum('amount');
            });
            $remainingAmount = $totalRevenue - $paidAmount;

            // Get installments due this month with status 'remaining'
            $dueInstallments = \App\Models\CourseInstallments::where('status', 'remaining')
                ->whereBetween('due_date', [$monthStart, $monthEnd])
                ->sum('amount');

            // Get course and student counts
            $courses = \App\Models\Courses::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $students = \App\Models\CourseStudents::whereBetween('created_at', [$monthStart, $monthEnd])->count();

            // Calculate KPI compared to previous month
            $revenueKPI = $previousData
                ? ($previousData['revenue'] != 0
                    ? (($totalRevenue - $previousData['revenue']) / $previousData['revenue']) * 100
                    : 0)
                : null;

            $report[] = [
                'month' => $current->locale('ar')->translatedFormat('F Y'),
                'month_key' => $current->format('Y-m'),
                'revenue' => $totalRevenue,
                'paid' => $paidAmount,
                'remaining' => $remainingAmount,
                'due_installments' => $dueInstallments,
                'students' => $students,
                'courses' => $courses,
                'revenue_kpi' => $revenueKPI,
            ];

            $previousData = [
                'revenue' => $totalRevenue,
                'students' => $students,
                'courses' => $courses,
            ];

            $current->addMonth();
        }

        return array_reverse($report);
    }

    protected function getCoursesReport($startDate, $endDate): array
    {
        return Courses::query()
            ->withCount(['CourseStudents'])
            ->with(['payments' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->with(['payment_transactions', 'installments']);
            }])
            ->when($this->data['course_id'], fn($q, $id) => $q->where('id', $id))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($course) use ($startDate, $endDate) {
                $payments = $course->payments;
                $students_count = $course->CourseStudents->count();

                $totalRevenue = $payments->sum('total_amount');
                $paidAmount = $payments->sum(function($payment) {
                    return $payment->payment_transactions->sum('amount');
                });
                $remainingAmount = $totalRevenue - $paidAmount;

                $dueInstallments = $payments->sum(function($payment) use ($startDate, $endDate) {
                    return $payment->installments
                        ->where('status', 'remaining')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('amount');

                });

                return [
                    'name' => $course->name,
                    'revenue' => $totalRevenue,
                    'paid' => $paidAmount,
                    'remaining' => $remainingAmount,
                    'due_installments' => $dueInstallments,
                    'students' => $students_count,
                    'total_fees' => $totalRevenue,
                ];
            })
            ->toArray();
    }



}
