<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\WaUser;
use App\WaUsed;
use App\WaReport;
use App\WaDeleted;
use App\WaMarkedDelete;

class CustomController extends Controller
{
    public function getcount()
    {
        $count = WaUser::all()->count();
        $response = ['status'=>'success', 'message'=>'Total users gotten successfully', 'data'=>['count'=>$count]];
        return json_encode($response);
    }
}
