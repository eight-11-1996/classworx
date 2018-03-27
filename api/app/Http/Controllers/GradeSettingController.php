<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GradeSetting;

class GradeSettingController extends ClassWorxController
{
    function __construct(){
      $this->model = new GradeSetting();
    }
}
