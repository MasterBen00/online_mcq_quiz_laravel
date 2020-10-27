<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Quiz\QuestionController;
use App\Http\Controllers\Quiz\QuizCategoryController;
use App\Http\Controllers\Quiz\QuizController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {
    // ...

    // public routes
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
    Route::post('/register',[ApiAuthController::class, 'register'])->name('register.api');

});

Route::middleware('auth:api')->group(function () {
    // our routes to be protected will go in here

    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//test routes go here

Route::middleware(['auth:api'])->group(function () {
    Route::get('/test', [\App\Http\Controllers\TestController::class, 'index'])->name('test');
});



//quiz
Route::group(['middleware' => ['auth:api', 'api.teacher']], function () {
    Route::apiResource('quiz', QuizController::class);
    //question
    Route::apiResource('question', QuestionController::class);
    Route::get('/teacher/quiz_list', [TeacherController::class, 'getAllQuizzes']);
    //Quiz category
    Route::apiResource('categories', QuizCategoryController::class);
    Route::get("/me", [ApiAuthController::class, 'getOwnInfo']);
    Route::get('/teacher/students', [TeacherController::class, 'getAllStudents']);
    Route::get('/teacher/students/scores/{student_id}', [TeacherController::class, 'getStudentScore']);
});

//student

Route::middleware(['auth:api'])->group(function () {
    Route::get('/student/quiz_list', [StudentController::class, 'getAllQuizzes']);
    Route::get('/student/instructor', [StudentController::class, 'getInstructor']);
    Route::get('/student/question_list/{quiz_id}', [StudentController::class, 'getAllQuestionsFromQuiz']);
    Route::post('/student/submit', [StudentController::class, 'submitAnswers']);
    Route::post('/student/submit_answers', [StudentController::class, 'submitAnswersByQuizId']);
});

Route::group(['middleware' => ['api.teacher']], function () {

    //no api here , wont work
});
