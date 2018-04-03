<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GradeSetting;

class GradeSettingController extends ClassWorxController
{
    function __construct(){
      $this->model = new GradeSetting();
      $this->notRequired = array(
        "semester_id",
        "course_id"
      );
    }
    public function update(Request $request){
      $data = $request->all();
      if(isset($data['semester_id']) === true){
        if($this->checkIfExistSemesterId($data['semester_id']) == true){
          $updateData = array();
          $updateData['quizzes_rate'] = $data["data"]['quizzes_rate'];
          $updateData['exams_rate'] = $data["data"]['exams_rate'];
          $updateData['attendance_rate'] = $data["data"]['attendance_rate'];
          $updateData['projects_rate'] = $data["data"]['projects_rate'];
          $updateData['passing_percentage_quizzes'] = $data["data"]['passing_percentage_quizzes'];
          $updateData['passing_percentage_exams'] = $data['data']['passing_percentage_exams'];
          $updateData['id'] = $data['data']['id'];
          $this->model = new GradeSetting();
          $this->updateDB($updateData);
        }else{
          $updateData = $data["data"];
          $updateData['semester_id'] = $data['semester_id'];
          $this->model = new GradeSetting();
          $this->insertDB($updateData);
        }
      }else{
        //
      } 
      return $this->response();
    }
    public function checkIfExistSemesterId($semesterId){
      $result = GradeSetting::where('semester_id', '=', $semesterId)->get();
      if(sizeof($result) > 0)
        return true;
        return false;
    }
    public function checkIfExistCourseId($courseId){
      $result = GradeSetting::where('course_id', '=', $courseId)->get();
      if(sizeof($result) > 0)
        return true;
        return false;
    }
}
