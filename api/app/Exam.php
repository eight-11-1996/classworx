<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends APIModel
{
    protected $table = 'exams';
    protected $fillable = ['course_id', 'description', 'type', 'start', 'end', 'timer'];
}
