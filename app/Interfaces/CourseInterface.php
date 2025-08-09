<?php

namespace App\Interfaces;
interface CourseInterface
{
    public function get_course($id);
    public function save_students($data);
    public function get_course_students($courseId, $search = '', $sortField = 'id', $sortDirection = 'asc', $perPage = 10);
    public function save_payment($course,$data);
    public function get_groups($course_id);
}
