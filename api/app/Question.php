<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends APIModel
{
    protected $table = 'questions';
    protected $fillable = ['quiz_id', 'exam_id', 'question', 'answer', 'order'];
}
