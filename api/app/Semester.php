<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends APIModel
{
    protected $table = "semesters";
    protected $fillable = ['account_id', 'description',  'start_date', 'end_date'];
}
