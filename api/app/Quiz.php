<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends APIModel
{
    protected $table = 'quizzes';
    protected $fillable = ['course_id', 'description', 'type', 'start', 'end', 'timer'];
}
