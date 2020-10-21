<?php


namespace App\Repository;


use App\Models\Question;

class QuestionRepository
{

    public function getAllQuestionFromQuiz($quiz_id)
    {
        return Question::where('quiz_id', $quiz_id)->get();
    }
}
