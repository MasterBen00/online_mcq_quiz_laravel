<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * @var StudentRepository
     */
    private $studentRepository;
    /**
     * @var TeacherRepository
     */
    private $teacherRepository;
    /**
     * @var QuizRepository
     */
    private $quizRepository;
    /**
     * @var QuestionRepository
     */
    private $questionRepository;


    /**
     * StudentController constructor.
     * @param StudentRepository $studentRepository
     * @param TeacherRepository $teacherRepository
     * @param QuizRepository $quizRepository
     * @param QuestionRepository $questionRepository
     */
    public function __construct(StudentRepository $studentRepository,
                                TeacherRepository $teacherRepository,
                                QuizRepository $quizRepository,
                                QuestionRepository $questionRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->teacherRepository = $teacherRepository;
        $this->quizRepository = $quizRepository;
        $this->questionRepository = $questionRepository;
    }

    public function getAllQuizzes()
    {
        $student = Auth::guard('api')->user();

        if ($student->type >= 1) {
            $response = ['result' => 'teachers are not authorized'];
            return response($response, 401);
        }
        $code = $student->teachers_code;

        $teacher = $this->teacherRepository->findTeacherByCode($code);
        $teacher_id = $teacher->id;

        $quiz_list = $this->quizRepository->getQuizListFromTeacher($teacher_id);

        $response = ['quizzes' => $quiz_list];

        return response($response, 200);
    }

    public function getAllQuestionsFromQuiz($quiz_id)
    {
        $question_list = $this->questionRepository->getAllQuestionFromQuiz($quiz_id);

        if ($question_list->isEmpty()) {
            $response = ['result' => 'no questions available'];

            return response($response, 404);
        }

        $response = ['questions' => $question_list];

        return response($response, 200);
    }
}
