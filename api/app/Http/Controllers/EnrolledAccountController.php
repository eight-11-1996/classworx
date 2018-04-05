<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EnrolledAccount;
use App\Course;
use Carbon\Carbon;
class EnrolledAccountController extends ClassWorxController
{
    function __construct(){
      $this->model = new EnrolledAccount();
      $this->notRequired = array(
        "declined_date",
        "confirmed_date"
      );
    }

    public function create(Request $request){
      $data = $request->all();

      $result = Course::where('enrolment_code', '=', $data['enrolment_code'])->get();
      if(sizeof($result) > 0){
        $exist =  $this->checkIfExistEnrolledAccount($data['account_id'], $result[0]['id']);
        if($exist == false){
          $insertData = array(
            'account_id'      => $data['account_id'],
            'course_id'       => $result[0]['id'],
            'status'          => 0
          );
          $this->model = new EnrolledAccount();
          $this->insertDB($insertData);
          return $this->response();
        }else{
          return response()->json(array('message' => "You're already enrolled this Course!", 'error' => true, "data" => null));
        }
      }else{
        return response()->json(array('message' => "Enrolment Code not Found!", 'error' => true, "data" => null));
      }
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $this->model = new EnrolledAccount();
      $result = $this->retrieveDB($data);
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response['data'][$i]['status_description'] = $this->statusDesciption($result[$i]['status']);
          $courseResult = Course::where('id', '=', $result[$i]['course_id'])->get();
          if(sizeof($courseResult) > 0){
            $this->response['data'][$i]['course_details'] = $courseResult[0];
          }else{
            $this->response['data'][$i]['course_details'] = null;
          }
          $i++;
        }
        return $this->response();
      }else{
        return response()->json(array("data" => null));
      }
    }
    public function statusDesciption($status){
      switch (intval($status)) {
        case 0:
          return "PENDING";
        case 1:
          return null;
        case 2:
          return "DECLINED";
      }
    }
    public function update(Request $request){
      $data = $request->all();

      if(intval($data['status']) == 1){
        $data['confirmed_date'] = Carbon::now();
      }else if(intval($data['status']) == 2){
        $data['declined_date'] = Carbon::now();
      }
      else{
        //
      }
      $this->model = new EnrolledAccount();
      $this->updateDB($data);
      return $this->response();
    }

    public function checkIfExistEnrolledAccount($accountId, $courseId){
      $result = EnrolledAccount::where('account_id', '=', $accountId)->where('course_id', '=', $courseId)->get();
      if(sizeof($result) > 0){
        return true;
      }else{
        return false;
      }
    }
}
