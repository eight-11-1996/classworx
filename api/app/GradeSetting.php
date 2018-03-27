<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradeSetting extends APIModel
{
    protected $table = 'grade_settings';
    protected $fillable = ['semester_id', 'course_id', 'quizes_rate', 'exams_rate', 'attendance_rate', 'passing_percentage_quizes', 'passing_percentage_exams'];
}
