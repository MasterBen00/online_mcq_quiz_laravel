<?php


namespace App\Repository;


use App\Models\Quiz;
use App\Models\User;

class TeacherRepository
{
    public function findTeacherByCode($code)
    {
        return User::where('teachers_code', $code)->first();
    }

}
