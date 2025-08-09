<?php

namespace App\Repository;

use App\Interfaces\CourseInterface;
use App\Models\CourseGroups;
use App\Models\CourseInstallments;
use App\Models\CoursePayments;
use App\Models\Courses;
use App\Models\CourseStudents;
use App\Models\PaymentTransactions;
use Carbon\Carbon;

class CourseRepository implements CourseInterface
{

    public function get_course($id)
    {
        return (Courses::with(['CourseStudents', 'CourseStudents.student'])->find($id));
    }

    /***********************************************/
    public function save_students($data)
    {
        //  dd($data);
        return CourseStudents::create($data);
    }

    public function get_course_students($courseId, $search = '', $sortField = 'id', $sortDirection = 'asc', $perPage = 10)
    {
        $query = CourseStudents::with('student')
            ->where('course_id', $courseId);

        if (!empty($search)) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            })->orWhere('type', 'like', "%{$search}%");
        }
        if ($sortField === 'student_name') {
            $query->join('tbl_students', 'tbl_course_students.student_id', '=', 'tbl_students.id')
                ->orderBy('tbl_students.full_name', $sortDirection)
                ->select('tbl_course_students.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        return $query->paginate($perPage);
    }

    public function save_payment($course, $data)
    {

      //  dd($data);
        $payment = [
            'course_id' => $data['course_id'],
            'group_id' => $data['group_id'],
            'student_id' => $data['student_id'],
            'total_amount' => $data['total_price'],
            'paid_amount' => $data['payment_type'] == 'cash' ? $data['total_price'] : $data['initial_payment'] ?? 0 ,
            'payment_type' => $data['payment_type'],
            'status' => $data['payment_type'] == 'cash' ? 'paid' : 'remaining',
        ];

        $paymentData = CoursePayments::create($payment);
       // dd($paymentData->id);
        if (($data['initial_payment'] ?? 0) > 0 ) {
            PaymentTransactions::create([
                'course_payment_id' => $paymentData->id,
                'amount' => $data['initial_payment'],
                'payment_date' => now(),
                'transaction_type' => 'initial_payment',
                'payment_method_id' => 1,
                'course_id' => $data['course_id'],
                'group_id' => $data['group_id'],
                'student_id' => $data['student_id'],
            ]);
        }

        if (($data['payment_type'] == 'cash') ) {
            PaymentTransactions::create([
                'course_payment_id' => $paymentData->id,
                'amount' => $data['total_price'],
                'payment_date' => now(),
                'transaction_type' => 'initial_payment',
                'payment_method_id' => 1,
                'course_id' => $data['course_id'],
                'group_id' => $data['group_id'],
                'student_id' => $data['student_id'],

            ]);
        }

        if ($data['payment_type'] === 'installment' || $data['payment_type'] === 'payment_installment') {
            $this->createInstallments($paymentData, $data);
        }

      //  dd($paymentData);
        return $paymentData;
    }

    protected function createInstallments($payment, $data)
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $numberOfInstallments = $data['number_of_installments'];
        $remainingAmount = $data['total_price'] - ($data['initial_payment'] ?? 0);
        $installmentAmount = $remainingAmount / $numberOfInstallments;
        $totalDuration = $startDate->diffInMonths($endDate);
        $interval = ceil($totalDuration / $numberOfInstallments);

        for ($i = 1; $i <= $numberOfInstallments; $i++) {

            $dueDate = $startDate->copy()
                ->addMonths(1)
                ->addMonths(($i - 1) * $interval);

            $currentAmount = ($i === $numberOfInstallments)
                ? $remainingAmount
                : $installmentAmount;

            CourseInstallments::create([
                'course_payment_id' => $payment->id,
                'amount' => round($currentAmount, 2),
                'due_date' => $dueDate,
                'course_id' => $payment->course_id,
                'student_id' => $payment->student_id,
                'group_id' => $payment->group_id,
                'status' => 'remaining',
            ]);

            $remainingAmount -= $currentAmount;
        }
    }

    public function get_groups($course_id)
    {
        return CourseGroups::where('course_id',$course_id)->get();
    }
}

