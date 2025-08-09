<?php

namespace App\Filament\Resources\SubscriptionPaymentResource\Pages;

use App\Filament\Resources\SubscriptionPaymentResource;
use App\Models\CoursePayments;
use App\Models\Courses;
use App\Services\CourseService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CreateSubscriptionPayment extends CreateRecord
{
    protected static string $resource = SubscriptionPaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();

        try {
           // dd($data);
            $course = Courses::findOrFail($data['course_id']);
            $courseService = app(CourseService::class);
            $courseStudent = $courseService->save_students([
                'course_id' => $data['course_id'],
                'student_id' => $data['student_id'],
                'group_id'     => $data['group_id'],
            ]);

            $payment_data = [
                'payment_type' =>  $data['payment_type'],
                'group_id'     => $data['group_id'],
                'course_id' => $data['course_id'],
                'start_date' => $course->start_date,
                'end_date' => $course->end_date,
                'student_id' => $data['student_id'],
                'total_price' =>$data['total_amount'] ?? 0,
                'duration' => $course->duration ?? 0,
                'installment_amount' => $data['installment_amount'] ?? 0,
                'number_of_installments' => $data['number_of_installments'] ?? 0,
                'initial_payment' => $data['payment_type']=='initial' ? $data['amount'] :  0,
            ];
            $record = $courseService->save_payment($courseStudent, $payment_data);
            //dd($payment);
            //$record = parent::handleRecordCreation($data);

            DB::commit();


            return $record;
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label(__('common.Save'))
        ];
    }
}
