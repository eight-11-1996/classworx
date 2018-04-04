<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceModel extends APIModel
{
    protected $table = 'resources';
    protected $fillable = ['resource_id', 'course_id', 'type', 'title', 'url'];
}
