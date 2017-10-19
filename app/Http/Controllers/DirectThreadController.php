<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DmThread;

class DirectThreadController extends MorfixController
{
  function __construct(){
    $this->model = new DmThread();
  }
}
