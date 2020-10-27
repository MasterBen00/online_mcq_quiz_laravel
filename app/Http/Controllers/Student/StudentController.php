<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Question\QuestionResource;
use App\Http\Resources\Quiz\QuizResource;
use App\Http\Resources\Quiz\StudentQuizResource;
use App\Models\Score;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $quiz_list_response = StudentQuizResource::collection($quiz_list);

        $response = ['quizzes' => $quiz_list_response];

        return response($response, 200);
    }

    public function getAllQuestionsFromQuiz($quiz_id)
    {
        $question_list = $this->questionRepository->getAllQuestionFromQuiz($quiz_id);

        if ($question_list->isEmpty()) {
            $response = ['result' => 'no questions available'];

            return response($response, 404);
        }

        $question_list_response = QuestionResource::collection($question_list);
        $response = ['questions' => $question_list_response];

        return response($response, 200);
    }

    public function submitAnswers(Request $request)
    {
        $student = Auth::guard('api')->user();
        $answer_list = $request['answers'];
        $obtained_score = 0;

        $answers = json_decode(json_encode($answer_list), FALSE);

        $quiz = null;
        $counter = 0;

        foreach ($answers as $answer) {

            $question_id = $answer->id;

            if ($counter == 0) {
                $question = $this->questionRepository->findQuestion($question_id);
                $quiz = $question->quiz;

                $counter++;
            }

            $correct_answer = $this->questionRepository->getCorrectAnswer($question_id);
            $submitted_answer = $answer->answer;

            if ($correct_answer == $submitted_answer) {
                $obtained_score = $obtained_score + 1;
            }

        }

        $score = new Score();

        $score->user_id = $student->id;
        $score->quiz_id = $quiz->id;
        $score->obtained_marks = $obtained_score;

        $score->save();


        $response = [
            'name' => $student->name,
            'obtained_score' => $obtained_score
        ];

        return response($response, 200);
    }

    public function submitAnswersByQuizId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:quizzes',
            'obtained_marks' => 'required|lte:10|gte:0',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $student = Auth::guard('api')->user();
        $obtained_score = $request['obtained_marks'];

        $score = new Score();

        $score->user_id = $student->id;
        $score->quiz_id = $request['id'];
        $score->obtained_marks = $obtained_score;

        $score->save();


        $response = [
            'name' => $student->name,
            'obtained_score' => $obtained_score
        ];

        return response($response, 200);
    }

    public function getInstructor()
    {
        $student = Auth::guard('api')->user();

        $teachers_code = $student->teachers_code;

        $teacher = $this->teacherRepository->findTeacherByCode($teachers_code);

        $response = [
            'teacher' => $teacher,
        ];

        return response($response, 200);
    }
}
