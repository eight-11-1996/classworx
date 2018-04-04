<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends APIModel
{
    protected $table = 'resources';
    protected $fillable = ['resource_id', 'course_id', 'type', 'title', 'url'];
}
