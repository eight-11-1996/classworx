<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountDegree;
class AccountDegreeController extends ClassWorxController
{
    function __construct(){
      $this->model = new AccountDegree();
      $this->notRequired = array(
        'school',
        'address',
        'school_id_number',
        'course',
        'year_started',
        'year_end'
      );
    }
}
