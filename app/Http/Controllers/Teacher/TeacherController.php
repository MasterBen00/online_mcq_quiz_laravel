<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Quiz\QuizResource;
use App\Repository\QuizRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * @var QuizRepository
     */
    private $quizRepository;


    /**
     * TeacherController constructor.
     * @param QuizRepository $quizRepository
     */
    public function __construct(QuizRepository $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function getAllQuizzes()
    {
        $teacher = Auth::guard('api')->user();

        if ($teacher->type <= 0) {
            $response = ['result' => 'Students are not authorized'];
            return response($response, 401);
        }
        $code = $teacher->teachers_code;

        $teacher_id = $teacher->id;


        $quiz_list = $this->quizRepository->getQuizListFromTeacher($teacher_id);

        $quiz_list_response = QuizResource::collection($quiz_list);

        $response = ['quizzes' => $quiz_list_response];

        return response($response, 200);
    }
}

