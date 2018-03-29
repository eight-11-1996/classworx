<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
class QuestionController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Question();

    	$this->notRequired = array(
    		'exam_id',
    		'quiz_id'
    	);
    }
}
