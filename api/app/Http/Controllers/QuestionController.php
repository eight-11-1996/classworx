<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\QuestionOption;
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

    public function retrieve(Request $request){
      $data = $request->all();
      $response = array(
        "data" => null,
        "message" => null
      );
      $result = null;
      if(isset($data['condition'])){
        $result = ($data['condition'][0]['column'] == 'quiz_id') ? Question::where('quiz_id', '=', $data['condition'][0]['value'])->get() : Question::where('exam_id', '=', $data['condition'][0]['value'])->get();
        if(sizeof($result) > 0){
          $i = 0;
          foreach ($result as $key) {
              $result[$i]['edit'] = false;
              $qOResult = QuestionOption::where('question_id', '=', $key->id)->orderBy('order', 'ASC')->get();
              if(sizeof($qOResult) > 0){
                $result[$i]['question_options'] = $qOResult;
              }else{
                $result[$i]['question_options'] = [];
              }
              $i++;
          }
          $response['data'] = $result;
        }else{
          $response['message'] = 'Empty';
        }
        return response()->json($response);
      }else{
        $this->retreveDB($data);
        return $this->response();
      }
    }
}
