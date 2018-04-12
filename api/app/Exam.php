<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends APIModel
{
    protected $table = 'exams';
    protected $fillable = ['course_id', 'description', 'available_date', 'available_time', 'time_limit', 'timer_flag', 'time_per_question', 'orders_setting', 'choices_setting'];
}
