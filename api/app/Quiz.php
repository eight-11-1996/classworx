<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends APIModel
{
    protected $table = 'quizes';
    protected $fillable = ['course_id', 'description', 'type', 'start', 'end', 'timer'];
}
