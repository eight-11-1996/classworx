<?php

namespace App\Http\Controllers;

use App\AccountProfile;
use Illuminate\Http\Request;

class AccountProfileController extends ClassWorxController
{
    function __construct(){
        $this->model = new AccountProfile();
    }
}
