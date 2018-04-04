<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\GradeSetting;
class CourseController extends ClassWorxController
{
    function __construct(){
      $this->model = new Course();
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $result = $this->retrieveDB($data);
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response["data"][$i]['grade_setting_flag'] = false;
          $this->response["data"][$i]['error_message'] = null;
          $gradeSettingResult = GradeSetting::where('course_id', '=', $key->id)->get();
          if(sizeof($gradeSettingResult) > 0){
              $this->response["data"][$i]["grade_settings_content"] = $gradeSettingResult;
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
          $i++;
        }
        return $this->response();
      }else{
        return $this->response();
      }
    }
}
