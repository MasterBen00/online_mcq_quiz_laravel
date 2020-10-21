<?php


namespace App\Repository;


use App\Models\Quiz;

class QuizRepository
{
    public function getQuizListFromTeacher($teacher_id)
    {
        return Quiz::where('user_id', $teacher_id)->get();
    }
}
