<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\App\ResourceModel

class ResourceController extends ClassWorxController
{
    function __construct(){
    	$this->model = new ResourceModel()
    }
}
