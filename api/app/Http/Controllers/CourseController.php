<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\EnrolledAccount;
use App\GradeSetting;
use App\Account;
use App\AccountInformation;
use App\AccountProfile;
use App\AccountDegree;
class CourseController extends ClassWorxController
{
    function __construct(){
      $this->model = new Course();
    }

    public function create(Request $request){
      $data = $request->all();
      $data["enrolment_code"] = $this->generateCode();
      $this->model = new Course();
      $this->insertDB($data);
      return $this->response();
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
    public function generateCode(){
      $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12);
      $codeExist = Course::where('enrolment_code', '=', $code)->get();
      if(sizeof($codeExist) > 0){
        $this->generateCode();
      }else{
        return $code;
      }
    }

    public function accounts(Request $request){
      $data = $request->all();
      $result = EnrolledAccount::where('course_id', '=', $data['course_id'])->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['edit_status_flag'] = false;
          $accountResult = Account::where('id', '=', $result[$i]['account_id'])->get();
          $accountInfoResult = AccountInformation::where('account_id', '=', $result[$i]['account_id'])->get();
          $accountProfileResult = AccountProfile::where('account_id', '=', $result[$i]['account_id'])->get();
          $accountDegreeResult = AccountDegree::where('account_id', '=', $result[$i]['account_id'])->get();
          $result[$i]['account'] = (sizeof($accountResult) > 0) ? $accountResult[0] : null;
          $result[$i]['account_information'] = (sizeof($accountInfoResult) > 0) ? $accountInfoResult[0] : null;
          $result[$i]['account_profile'] = (sizeof($accountProfileResult) > 0) ? $accountProfileResult[0] : null;
          $result[$i]['account_degree'] = (sizeof($accountDegreeResult) > 0) ? $accountDegreeResult[0] : null;
          $i++;
        }
        return response()->json(array('data' => $result));
      }else{
        return response()->json(array('data' => null));
      }
    }
}
