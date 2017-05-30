<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\InstagramProfile;
use Response;

class DirectMessageLogsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
}
