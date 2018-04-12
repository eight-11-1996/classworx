<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends APIModel
{
    protected $table = 'quizzes';
    protected $fillable = ['course_id', 'description', 'available_date', 'available_time', 'time_limit', 'timer_flag', 'time_per_question', 'orders_setting', 'choices_setting'];
}
