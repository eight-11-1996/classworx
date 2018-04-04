<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResourceViewer;
class ResourceViewerController extends ClassWorxController
{
    function __construct(){
      $this->model = new ResourceViewer();
    }
}
