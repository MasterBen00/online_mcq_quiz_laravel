<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'obtained_marks',
        'total_marks',
    ];

    public function obtainedBy()
    {
        return $this->belongsTo('App\Models\User');
    }
}
