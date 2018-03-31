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

    public function create(Request $request){
      $data = $request->all();
      $data['order'] = (isset($data['quiz_id']) == true) ? $this->getLatestOrderByQuizId($data['quiz_id']) : $this->getLatestOrderByExamId($data['exam_id']);
      $this->model = new Question();
      $this->insertDB($data);
      return $this->response();
    }

    public function getLatestOrderByQuizId($quizId){
      $result = Question::where('quiz_id', '=', $quizId)->orderBy('order', 'DESC')->limit(1)->get();
      if(sizeof($result) > 0)
        return $result[0]->order + 1;
        return 1;
    }
    public function getLatestOrderByExamId($examId){
      $result = Question::where('exam_id', '=', $examId)->orderBy('order', 'DESC')->limit(1)->get();
      if(sizeof($result) > 0)
        return $result[0]->order + 1;
        return 1;
    }
}
