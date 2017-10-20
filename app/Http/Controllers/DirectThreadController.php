<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DmJob;

class DirectThreadController extends MorfixController
{
	function __construct()
	{
		$this->model = new DmJob();
	}

}
