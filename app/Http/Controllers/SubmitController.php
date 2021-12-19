<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\WaUser;
use App\WaUsed;
use App\WaReport;

class SubmitController extends Controller
{
    public function index() 
    {
        $data = request()->validate([
            'name' => [],
            'country_code' => [],
            'number' => [],
        ]);

        @$name = $data['name'];
        @$country_code = $data['country_code'];
        @$number = $data['number'];
        @$full_number = $country_code.$number;

        if (!isset($name) || !isset($country_code) || !isset($number)) {
            $response = ['status'=>'error', 'message'=>'One of the required parameters are empty. Try Re-submitting.', 'data'=>null];
            return json_encode($response);
        }

        $user = WaUser::where('full_number', $full_number)->first();
        if ($user != null) {
            $response = ['status'=>'error', 'message'=>'This number has been submitted before. Use the Get VCF button to download VCF files', 'data'=>null];
            return json_encode($response);
        }
        
        if (!preg_match("/[A-Za-z0-9 ]/", $name)) {
            $response = ['status'=>'error', 'message'=>'Only letters and numbers are allowed in name field', 'data'=>null];
            return json_encode($response);
    		
    	}
        
        if (preg_match('/wassapviews|wassapview/', strtolower($name))) {
            $response = ['status'=>'error', 'message'=>'This name can\'t be used', 'data'=>null];
            return json_encode($response);
        }

        $action = WaUser::create([
            'name' => $name.' WG',
            'country_code' => $country_code,
            'number' => $number,
            'full_number' => $full_number,
            'status'=>'active',
        ]);
        if ($action) {
            $response = [
                'status' => 'success', 
                'message' => 'Number submitted successfully', 
                'data'=> [
                    'name' => $name.' WG', 
                    'country_code' => $country_code, 
                    'number' => $number, 
                    'full_number' => $full_number,
                ],
            ];
        }else {
            $response = ['status'=>'error', 'message'=>'An error occured', 'data'=>null];
        }
        
        return json_encode($response);
    }
}
