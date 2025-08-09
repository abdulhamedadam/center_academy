<?php
namespace App\Services;

use App\Interfaces\CourseInterface;
use App\Models\CourseStudents;

class CourseService
{
    protected $course_repository;

    public function __construct(CourseInterface $course_repository)
    {
        $this->course_repository = $course_repository;
    }
    /*************************************************/
    public function get_course($id)
    {
        return $this->course_repository->get_course($id);
    }
    /*************************************************/
    public function save_students($data)
    {
        return  $this->course_repository->save_students($data);
    }
    /**************************************************/
    public function get_course_students($courseId, $search = '', $sortField = 'id', $sortDirection = 'asc', $perPage = 10)
    {
        return  $this->course_repository->get_course_students($courseId, $search = '', $sortField = 'id', $sortDirection = 'asc', $perPage = 10);

    }
    /***************************************************/
    public function save_payment($course,$data)
    {
        return  $this->course_repository->save_payment($course,$data);
    }
    //------------------------------------------------------------------------------------------------------------------
    public function get_groups($course_id)
    {
        return  $this->course_repository->get_groups($course_id);
    }
}
