<?php

namespace App\Http\Resources\Student;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentScoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "quiz_id" => $this->quiz_id,
            "obtained_marks" => $this->obtained_marks,
            "total_marks" => $this->total_marks,
            "quiz_name" => Quiz::find($this->quiz_id)->title,
            "user_name" => User::find($this->user_id)->name,
            "updated_at" => $this->updated_at->diffForHumans(),
        ];

    }
}
