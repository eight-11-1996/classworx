<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends APIModel
{
    protected $table = 'courses';
    protected $fillable = ['semester_id', 'code', 'description', 'units', 'time_start', 'time_end', 'days'];
}
