<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Quiz\QuizResource;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\Student\StudentScoreResource;
use App\Repository\QuizRepository;
use App\Repository\TeacherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * @var QuizRepository
     */
    private $quizRepository;
    /**
     * @var TeacherRepository
     */
    private $teacherRepository;


    /**
     * TeacherController constructor.
     * @param QuizRepository $quizRepository
     * @param TeacherRepository $teacherRepository
     */
    public function __construct(QuizRepository $quizRepository, TeacherRepository $teacherRepository)
    {
        $this->quizRepository = $quizRepository;
        $this->teacherRepository = $teacherRepository;
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

    public function getAllStudents()
    {
        $teacher = Auth::guard('api')->user();
        $teachers_code = $teacher->teachers_code;

        $students = $this->teacherRepository->getAllStudentsByCode($teachers_code);

        $response = ['students' => $students];

        return response($response, 200);

    }

    public function getStudentScore($student_id)
    {
        $score_list = $this->teacherRepository->getStudentScore($student_id);
        $score_list_response = StudentScoreResource::collection($score_list);

        $response = ['students_score' => $score_list_response];

        return response($response, 200);
    }
}

