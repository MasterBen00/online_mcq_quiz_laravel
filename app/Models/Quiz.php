<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function question()
    {
        return $this->hasMany('App\Models\Question');
    }

    public function quizCategory()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User');
    }
}
