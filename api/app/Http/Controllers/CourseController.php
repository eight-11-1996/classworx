<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;

class CourseController extends ClassWorxController
{
    function __construct(){
      $this->model = new Course();
    }
}
