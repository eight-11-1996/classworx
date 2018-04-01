<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use App\Question;
class QuizController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Quiz();
    }

    public function retrieve(Request $request){
      $response = array(
        "data" => null,
        "message" => null
      );
      $data = $request->all();
      $result = Quiz::where($data['condition'][0]['column'], '=', $data['condition'][0]['value'])->get();

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $questionResult = Question::where('quiz_id', '=', $key->id)->get();
          $result[$i++]['total_questions'] = sizeof($questionResult);
        }
        $response['data'] = $result;
      }else{
        $response['message'] = "Empty";
      }
      return response()->json($response);
    }
}
