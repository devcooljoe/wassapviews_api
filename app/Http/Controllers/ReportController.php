<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\WaUser;
use App\WaUsed;
use App\WaReport;

class ReportController extends Controller
{
    public function index()
    {
        $data = request()->validate([
            'full_number' => [],
            'reason' => [],
            'user_agent'=> [],
        ]);

        @$full_number = $data['full_number'];
        @$reason = $data['reason'];
        @$user_agent = $data['user_agent'];

        if (!isset($full_number) || !isset($reason) || !isset($user_agent)) {
            $response = ['status'=>'error', 'message'=>'One of the required parameters are empty. Try Re-submitting.', 'data'=>null];
            return json_encode($response);
        }

        
        
        $check = WaReport::where('user_agent', $user_agent)->where('full_number', $full_number)->first();
        $checked_user = WaUser::where('full_number', $full_number)->first();
        if ($checked_user == null) {
            $response = ['status'=>'error', 'message'=>'This contact is not on Wassapgains!', 'data'=>null];
            return json_encode($response);
        }
        if ($check == null) {
            $action = WaReport::create([
                'user_agent'=>$user_agent,
                'full_number'=>$full_number,
                'reason'=>$reason,
            ]);
            $check_report = WaReport::where('full_number', $full_number)->get();
            if ($check_report->count() >= 5) {
                $checked_user->update([
                    'status'=>'banned',
                ]);
            }

            if ($action) {
                $response = ['status'=>'success', 'message'=>'This contact has been reported successfully', 'data'=>null];
            }else {
                $response = ['status'=>'error', 'message'=>'An error occured', 'data'=>null];
            }
            return json_encode($response);

        } else {
            $response = ['status'=>'error', 'message'=>'This contact has been reported by you previously', 'data'=>null];
            return json_encode($response);
        }
    }
}
