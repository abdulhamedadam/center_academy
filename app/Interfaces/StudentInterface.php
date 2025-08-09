<?php


namespace App\Interfaces;


interface StudentInterface
{
    public function get_all();

    public function get_all_active();

    public function all_student_not_in_course($id);

    public function save_students($data);

    public function get_student($id);
}
