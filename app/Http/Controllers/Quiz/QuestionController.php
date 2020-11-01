<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255|min:10',
            'option1' => 'required|string|max:20|min:1',
            'option2' => 'required|string|max:20|min:1',
            'option3' => 'required|string|max:20|min:1',
            'option4' => 'required|string|max:20|min:1',
            'answer' => 'required|string|max:20|min:1',
            'quiz_id' => 'required',
        ]);

        if (Question::where('quiz_id', $request['quiz_id'])->count() >= 10) {
            $validator->errors()->merge(array('this quiz already have maximum 10 questions'));
        }

        if ($request['answer'] != null && $request['answer'] != $request['option1'] && $request['answer'] != $request['option2'] &&
            $request['answer'] != $request['option3'] && $request['answer'] != $request['option4']) {

            $validator->errors()->merge(array('answer must be in these four options'));
        }

        if ($request['quiz_id'] != null && Quiz::where('id', $request['quiz_id'])->doesntExist()) {

            $validator->errors()->merge(array('quiz not found'));
            return response(['errors' => $validator->errors()->all()], 422);
        }


        //if ($validator->fails()) {
        if (!empty($validator->errors()->messages())) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $quiz = Quiz::find($request['quiz_id']);
        $teacher = Auth::guard('api')->user();

        $question = new Question();

        $question->question = $request['question'];
        $question->option1 = $request['option1'];
        $question->option2 = $request['option2'];
        $question->option3 = $request['option3'];
        $question->option4 = $request['option4'];
        $question->answer = $request['answer'];

        $quiz->question()->save($question);

        $response = [
            'question' => $question,
            'quiz' => $quiz,
            'createdBy' => $teacher
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255|min:10',
            'option1' => 'required|string|max:20|min:1',
            'option2' => 'required|string|max:20|min:1',
            'option3' => 'required|string|max:20|min:1',
            'option4' => 'required|string|max:20|min:1',
            'answer' => 'required|string|max:20|min:1',
            //'quiz_id' => 'required',
        ]);

//        if ($request['quiz_id'] != null && Quiz::where('id', $request['quiz_id'])->doesntExist()) {
//
//            $validator->errors()->merge(array('quiz not found'));
//            return response(['errors' => $validator->errors()->all()], 422);
//        }
        if ($request['answer'] != $request['option1'] && $request['answer'] != $request['option2'] &&
            $request['answer'] != $request['option3'] && $request['answer'] != $request['option4']) {

            $validator->errors()->merge(array('answer must be in these four options'));
        }

        if ($id != null && Question::where('id', $id)->doesntExist()) {

            $validator->errors()->merge(array('question not found'));
            //return response(['errors' => $validator->errors()->all()], 422);
        }

        if (!empty($validator->errors()->messages())) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $question = Question::find($id);

        $question->question = $request['question'];
        $question->option1 = $request['option1'];
        $question->option2 = $request['option2'];
        $question->option3 = $request['option3'];
        $question->option4 = $request['option4'];
        $question->answer = $request['answer'];

        $question->update();

        $response = [
            'question' => $question,
        ];

        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id != null && Question::where('id', $id)->doesntExist()) {

            return response(['errors' => 'question not found'], 422);
        }

        $question = Question::destroy($id);

        return response(['result' => 'deleted'], 200);
    }
}
