<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exam;
use App\Question;
class ExamController extends ClassWorxController
{
    function __construct(){
      $this->model = new Exam(); 
      $this->notRequired = array(
        'time_per_question'
      );
    }
    public function retrieve(Request $request){
      $response = array(
        "data" => null,
        "message" => null
      );
      $data = $request->all();
      $result = Exam::where($data['condition'][0]['column'], '=', $data['condition'][0]['value'])->get();

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $questionResult = Question::where('exam_id', '=', $key->id)->get();
          $result[$i++]['total_questions'] = sizeof($questionResult);
        }
        $response['data'] = $result;
      }else{
        $response['message'] = "Empty";
      }
      return response()->json($response);
    }
}
