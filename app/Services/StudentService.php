<?php


namespace App\Services;


use App\Interfaces\CourseInterface;
use App\Interfaces\StudentInterface;
use function Symfony\Component\HttpKernel\Log\record;

class StudentService
{
    protected $student_repository;

    public function __construct(StudentInterface $student_repository)
    {
        $this->student_repository = $student_repository;
    }

    /*************************************/
    public function get_all_students()
    {
        return $this->student_repository->get_all();
    }
    /**************************************/
    public function get_all_active_students()
    {
        return $this->student_repository->get_all_active();
    }
    /****************************************/
    public function all_student_not_in_course($id)
    {
        return $this->student_repository->all_student_not_in_course($id);
    }
    /*****************************************/
    public function get_student($id)
    {
        return $this->student_repository->get_student($id);
    }



}
