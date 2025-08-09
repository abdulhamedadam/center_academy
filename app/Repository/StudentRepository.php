<?php


namespace App\Repository;


use App\Filament\Resources\CoursesResource\Pages\CourseStudents;
use App\Interfaces\StudentInterface;
use App\Models\Students;

class StudentRepository implements StudentInterface
{

    public function get_all()
    {
        return Students::all();
    }

    /******************************************/
    public function get_all_active()
    {
        return Students::where('status', '1')->get();
    }

    /******************************************/
    public function all_student_not_in_course($courseId)
    {
        return Students::whereDoesntHave('courses', function ($query) use ($courseId) {
            $query->where('tbl_courses.id', $courseId);
        })

            ->get();
    }

    public function save_students($data)
    {
        return CourseStudents::create($data);
    }

    public function get_student($id)
    {
        return Students::with(['courses','complaints','assignmentSubmissions','testResults','coursePayments','attendances'])->find($id);
    }
}
