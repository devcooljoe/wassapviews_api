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

class GetVcfController extends Controller
{
    public function index()
    {
        $data = request()->validate(['country_code' => [], 'number' => [], ]);

        @$country_code = $data['country_code'];
        @$number = $data['number'];
        @$full_number = $country_code . $number;

        if (!isset($country_code) || !isset($number))
        {
            $response = ['status' => 'error', 'message' => 'One of the required parameters are empty. Try Re-submitting', 'data' => null];
            return json_encode($response);
        }

        $check = WaReport::where('full_number', $full_number)->get();
        if ($check->count() >= 5)
        {
            $response = ['status' => 'error', 'message' => 'This account has been banned', 'data' => null];
            return json_encode($response);
        }

        $user = WaUser::where('full_number', $full_number)->first();
        if ($user == null)
        {
            $response = ['status' => 'error', 'message' => 'This number has not been submitted. Submit this contact first', 'data' => null];
            return json_encode($response);
        }

        $user_used = WaUsed::where('full_number', $full_number)->first();
        if ($user_used == null)
        {
            $last_id = 0;
        }
        else
        {
            $last_fetched = $user_used->used_number;
            $fetched_user = WaUser::where('full_number', $last_fetched)->first();
            if ($fetched_user == null)
            {
                $last_id = 0;
            }
            else
            {
                $last_id = $fetched_user->id;
            }
        }

        // Check for contacts that has not been fetched
        $unfetched = WaUser::orderBy('id', 'ASC')->where('id', '>', $last_id)->get();
        if ($unfetched->count() < 1)
        {
            $response = ['status' => 'share', 'message' => 'Share on Your WhatsApp Status for More', 'data' => null];
            return json_encode($response);
        }
        else
        {

            $file_name = date('d-m-Y') . '-' . uniqid() . ".vcf";
            $arr = [];
            $str = '';
            foreach ($unfetched as $user)
            {
                array_push($arr, $user->full_number);
                // Create the VCF file. Do not add any tab or space in the structure below. Should be left that way.
                $str .= 'BEGIN:VCARD
VERSION:2.1
FN:' . $user->name . '
TEL;CELL:' . $user->full_number . '
END:VCARD
';
            }

            $last_num = end($arr);
            $user_used = WaUsed::where('full_number', $full_number)->first();
            if ($user_used == null)
            {
                WaUsed::create(['full_number' => $full_number, 'used_number' => $last_num, ]);
            }
            else
            {
                $user_used->update(['used_number' => $last_num, ]);
            }
            // Write to the VCF file
            $file = fopen('vcf/' . $file_name, 'w+');
            fwrite($file, $str);
            fclose($file);

            $user = WaUser::where('full_number', $full_number)->first();
            $response = ['status' => 'success', 'message' => 'VCF file requested successfully', 'data' => ['name' => $user->name, 'country_code' => $user->country_code, 'number' => $user->number, 'full_number' => $user->full_number, 'path' => route('index') . '/vcf/' . $file_name, 'file_name' => $file_name, ], ];
            return json_encode($response);
        }
    }

    public function getall()
    {
        $data = request()->validate(['country_code' => [], 'number' => [], ]);

        @$country_code = $data['country_code'];
        @$number = $data['number'];
        @$full_number = $country_code . $number;

        if (!isset($country_code) || !isset($number))
        {
            $response = ['status' => 'error', 'message' => 'One of the required parameters are empty. Try Re-submitting', 'data' => null];
            return json_encode($response);
        }

        $check = WaReport::where('full_number', $full_number)->get();
        if ($check->count() >= 5)
        {
            $response = ['status' => 'error', 'message' => 'This account has been banned', 'data' => null];
            return json_encode($response);
        }

        $user = WaUser::where('full_number', $full_number)->first();
        if ($user == null)
        {
            $response = ['status' => 'error', 'message' => 'This number has not been submitted. Submit this contact first', 'data' => null];
            return json_encode($response);
        }
        $unfetched = WaUser::all();
        $file_name = date('d-m-Y') . '-' . uniqid() . ".vcf";
        $str = '';
        foreach ($unfetched as $user)
        {
            // Create the VCF file. Do not add any tab or space in the structure below. Should be left that way.
            $str .= 'BEGIN:VCARD
VERSION:2.1
FN:' . $user->name . '
TEL;CELL:' . $user->full_number . '
END:VCARD
';
        }
        // Write to the VCF file
        $file = fopen('vcf/' . $file_name, 'w+');
        fwrite($file, $str);
        fclose($file);

        $user = WaUser::where('full_number', $full_number)->first();
        $response = ['status' => 'success', 'message' => 'VCF file requested successfully', 'data' => ['name' => $user->name, 'country_code' => $user->country_code, 'number' => $user->number, 'full_number' => $user->full_number, 'path' => route('index') . '/vcf/' . $file_name, 'file_name' => $file_name, ], ];
        return json_encode($response);
    }


    public function fetchoutdated() 
    {
        $data = request()->validate(['country_code' => [], 'number' => [], ]);

        @$country_code = $data['country_code'];
        @$number = $data['number'];
        @$full_number = $country_code . $number;

        $last_marked = WaMarkedDelete::where('full_number', $full_number)->first();
        if ($last_marked == null) {
            $last_id = 0;
        }else {
            $last_id = $last_marked->deleted_id;
        }
        $deleted_users = WaDeleted::orderBy('id', 'ASC')->where('id', '>', $last_id)->get();
        $count = $deleted_users->count();
        if ($count > 0) {
            $phones = [];
            foreach ($deleted_users as $user) {
                array_push($phones, $user->full_number);
            }
            if ($last_marked == null) {
                WaMarkedDelete::create(['full_number'=>$full_number, 'deleted_id'=>$last_id+$count]);
            }else {
                $last_marked->update(['deleted_id'=>$last_deleted_user->id]);
            }
            $response = ['status' => 'success', 'message' => 'Contacts requested successfully', 'data' => $phones,];
            return json_encode($response);
        } else {
            $response = ['status' => 'error', 'message' => 'No outdated contacts for now'];
            return json_encode($response);
        }
    }
}

