<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Semester;
use App\GradeSetting;
class SemesterController extends ClassWorxController
{
    function __construct(){
      $this->model = new Semester();
    }

    public function retrieve(Request $request){
      $this->rawRequest = $request;
      $this->retrieveDB($request->all());
      $result =  $this->response['data'];

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response["data"][$i]['grade_flag'] = false;
          $this->response["data"][$i]['error_message'] = null;
          if(intval($result[$i]['grade_setting']) == 0){
            $gradeSetting = GradeSetting::where('semester_id', '=', $result[$i]['id'])->get();
            if(sizeof($gradeSetting) > 0){
              $this->response["data"][$i]["grade_settings_content"] = $gradeSetting;
            }else{
              $this->response["data"][$i]["grade_settings_content"] = array(
                array(
                  "quizzes_rate" => 0,
                  "exams_rate" => 0,
                  "attendance_rate" => 0,
                  "projects_rate" => 0,
                  "passing_percentage_quizzes" => 0,
                  "passing_percentage_exams" => 0
                )
              );
            }
          }else{
            $this->response["data"][$i]["grade_settings_content"] = null;
          }
          $i++;
        }
      }else{
        //
      }
      return $this->response();
    }
}
