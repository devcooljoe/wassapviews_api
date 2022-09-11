<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\WaUser;
use App\WaUsed;
use App\WaReport;
use App\WaAdvert;

class CustomController extends Controller
{
    public function getcount()
    {
        $count = WaUser::all()->count();
        if (strlen($count) < 7) {
            $count = round($count/1000, 2, PHP_ROUND_HALF_DOWN).'K';
        } else if (strlen($count) < 10) {
            $count = round($count/100000, 2, PHP_ROUND_HALF_DOWN).'M';
        } else {
            $count = $count;
        }
        $response = ['status'=>'success', 'message'=>'Total users gotten successfully', 'data'=>['count'=>$count]];
        return json_encode($response);
    }
    
    public function gettotalcount()
    {
        $count = WaUser::all()->count();
        $response = ['status'=>'success', 'message'=>'Total users gotten successfully', 'data'=>['count'=>$count]];
        return json_encode($response);
    }
    
    public function getads() 
    {
        $ads = WaAdvert::orderBy('id', 'DESC')->get();
        $ad_list = [];
        foreach ($ads as $ad) {
            array_push($ad_list, ['image'=>$ad->image, 'link'=>$ad->link]);
        }
        $response = ['status'=>'success', 'message'=>'Ads fetched successfully', 'data'=>$ad_list];
        return json_encode($response);
    }
}
