<?php


namespace App\Repository;


use App\Models\Quiz;
use App\Models\Score;
use App\Models\User;

class TeacherRepository
{
    public function findTeacherByCode($code)
    {
        return User::where('teachers_code', $code)->first();
    }

    public function getAllStudentsByCode($code)
    {
        return User::where([['teachers_code', $code], ['type', 0]])->get();
    }

    public function getStudentScore($id)
    {
        return Score::where('user_id', $id)->get();
    }
}
