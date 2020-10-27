<?php

namespace App\Http\Resources\Quiz;

use App\Models\Category;
use App\Models\Question;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class StudentQuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       // return parent::toArray($request);

        return [
            "id" => $this->id,
            "category_id" => $this->category_id,
            "user_id" => $this->user_id,
            "title" => $this->title,
            "description" => $this->description,
            "category_name" => Category::find($this->category_id)->subject,
            "user_name" => User::find($this->user_id)->name,
            "total_questions" => Question::where('quiz_id', $this->id)->count(),
            "participated" => Score::where([['quiz_id', $this->id], ['user_id', Auth::guard('api')->user()->id]])->exists(),
            "obtained_score" => Score::where([['quiz_id', $this->id], ['user_id', Auth::guard('api')->user()->id]])->first("obtained_marks"),
            "created_at" => $this->created_at->diffForHumans(),
            "updated_at" => $this->updated_at->diffForHumans(),
        ];
    }
}
