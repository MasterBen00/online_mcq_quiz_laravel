<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
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
            'title' => 'required|string|max:30|min:4',
            'description' => 'required|string|max:255|min:20',
            'category_id' => 'required',
        ]);


//        if (!$validator->fails()) {
//            $validator->sometimes('category_id', 'required', function ($input) {
//
//                $category_count = Category::where('id', $input->category_id)->count();
//
//                return $category_count == 0;
//            });
//        }

        if ($request['category_id'] != null && Category::where('id', $request['category_id'])->doesntExist()) {
            $validator->errors()->merge(array('category not found'));
            return response(['errors' => $validator->errors()->all()], 422);
            //return response(['errors' => 'category not found'], 422);
        }


        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $category = Category::find($request['category_id']);
        $teacher = Auth::guard('api')->user();

        $quiz = new Quiz();

        $quiz->title = $request['title'];
        $quiz->description = $request['description'];

        $quiz->category_id = $category->id;
        $quiz->user_id = $teacher->id;


        $quiz->save();
        //$quiz = Quiz::create($request->toArray());


//        $category->quizzes()->save($quiz);
//        $teacher->quizzes()->save($quiz);


        $response = ['quiz' => $quiz];

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
            'title' => 'required|string|max:30|min:4',
            'description' => 'required|string|max:255|min:20',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $quiz = Quiz::find($id);

        $quiz->title = $request['title'];
        $quiz->description = $request['description'];

        $quiz->update();

        $response = ['quiz' => $quiz];

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
        if ($id != null && Quiz::where('id', $id)->doesntExist()) {

            return response(['errors' => 'quiz not found'], 422);
        }

        $quiz = Quiz::destroy($id);

        return response(['result' => 'deleted'], 200);
    }
}
