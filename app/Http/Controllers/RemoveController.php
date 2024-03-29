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

class RemoveController extends Controller
{
    public function index()
    {
        $data = request()->validate(['full_number' => [], ]);

        $user = WaUser::where('full_number', $data['full_number'])->first();
        if ($user == null)
        {
            $response = ['status' => 'error', 'message' => 'This contact is not on Wassapgains', 'data' => null];
            return json_encode($response);
        }
        else
        {

            WaDeleted::create(['full_number'=>$data['full_number']]);
            $action = $user->delete();
            $used = WaUsed::where('full_number', $data['full_number'])->first();
            if ($used != null) 
            {
                $used->delete();
            }
            if ($action)
            {
                $response = ['status' => 'success', 'message' => 'Contact has been deleted successfully', 'data' => null];
            }
            else
            {
                $response = ['status' => 'error', 'message' => 'An error occured', 'data' => null];
            }
            return json_encode($response);
        }

    }
}

