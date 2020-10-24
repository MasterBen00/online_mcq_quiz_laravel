<?php


namespace App\Repository;


use App\Models\Question;

class QuestionRepository
{

    public function getAllQuestionFromQuiz($quiz_id)
    {
        return Question::where('quiz_id', $quiz_id)->get();
    }

    public function getCorrectAnswer($id)
    {
        return Question::where('id', $id)->value('answer');
    }

    public function findQuestion($id)
    {
        return Question::find($id);
    }
}
