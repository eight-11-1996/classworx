<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\App\Resource;

class ResourceController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Resource()
    }
}
