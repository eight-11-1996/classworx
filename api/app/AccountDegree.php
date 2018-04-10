<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountDegree extends APIModel
{
    protected $table = 'account_degrees';
    protected $fillable = ['account_id', 'school', 'address', 'school_number', 'course', 'year_started', 'year_end'];
}
