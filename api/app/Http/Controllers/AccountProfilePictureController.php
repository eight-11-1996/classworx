<?php

namespace App\Http\Controllers;

use App\AccountProfilePicture;
use Illuminate\Http\Request;

class AccountProfilePictureController extends TalkController
{
    function __construct(){
        $this->model = new AccountProfilePicture();
    }
}
