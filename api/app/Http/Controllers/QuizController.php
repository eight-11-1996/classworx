<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
class QuizController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Quiz();
    }
}
