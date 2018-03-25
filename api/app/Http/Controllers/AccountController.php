<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class AccountController extends ClassWorxController
{
     function __construct(){  
        $this->model = new Account();
        $this->validation = array(  
          "email" => "unique:accounts",
          "username"  => "unique:accounts"
        );
    } 

    public function create(Request $request){
     $request = $request->all();
     $request['password'] = Hash::make($request['password']);
     $request['account_type'] = "USER";
     $this->insertDB($request);
     return $this->response();
    }

    public function update(Request $request){ 
      $request    = $request->all();
      $text = array('email' => $request['username']);
      $condition = null;
      $flag = false;
      if($this->customValidate($text) == true){
        $condition = array('email' => $request['username']);
        $flag = true;
      }
      else{
        $condition = array('username' => $request['username']);
      }
      $result = DB::table('accounts')->where($condition)->get();
      $parameter = null;
      if(sizeof($result) > 0){
        $parameter['id'] = $result[0]->id;
        $parameter['password'] = Hash::make($request['password']);
      }
      else{
        return array('message' => ['error' => [
          'status' => 100
        ]]);
      }  
      $request['password'] = $this->hashPassword($request['password']);
      $this->updateDB($parameter);
      return $this->response();
    }
    public function hashPassword($password){
      $data['password'] = Hash::make($password);
      return $data;
    }
    public function customValidate($text){
      $validation = array('email' => 'required|email'); 
      return $this->validateReply($text, $validation);
    }

    public function validateReply($text, $validation){
      $validator = Validator::make($text, $validation);
      if($validator->fails()){
        return false;
      }
      else
        return true;
    }
}