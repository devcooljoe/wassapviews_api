<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\WaUser;
use App\WaUsed;
use App\WaReport;
use App\WaSubscription;
use App\WaDeleted;
use App\WaMarkedDelete;

class PremiumController extends Controller
{
    public function activatepremium()
    {
        $data = request()->validate(['full_number' => [], 'plan' => [], ]);
        @$full_number = $data['full_number'];
        @$plan = $data['plan'];

        if (!isset($full_number) || !isset($plan))
        {
            $response = ['status' => 'error', 'message' => 'One of the required parameters are empty. Try Re-submitting', 'data' => null];
            return json_encode($response);
        }
        $start = date('d-m-Y');
        if ($plan == 'yearly')
        {
            $end = date('d-m-Y', strtotime('+365 days'));
        }
        else
        {
            $end = date('d-m-Y', strtotime('+30 days'));
        }
        $user = WaUser::where('full_number', $full_number)->first();
        if ($user != null)
        {
            $user->subscription()
                ->update(['plan' => $plan, 'start' => $start, 'end' => $end, ]);
            if ($user)
            {
                $response = ['status' => 'success', 'message' => 'Premium plan activated successfully.', 'data' => ['plan' => $plan, 'end' => $end, ], ];
                return json_encode($response);
            }
            else
            {
                $response = ['status' => 'error', 'message' => 'An error occured.', 'data' => null];
                return json_encode($response);
            }
        }
        else
        {
            $response = ['status' => 'error', 'message' => 'This number does not exist on Wassapviews.', 'data' => null];
            return json_encode($response);
        }

    }

    public function getpremiumstatus()
    {
        $data = request()->validate(['full_number' => [], ]);
        @$full_number = $data['full_number'];
        if (!isset($full_number))
        {
            $response = ['status' => 'error', 'message' => 'One of the required parameters are empty. Try Re-submitting', 'data' => null];
            return json_encode($response);
        }
        $user = WaUser::where('full_number', $full_number)->first();
        if ($user == null)
        {
            $response = ['status' => 'success', 'message' => 'Subscription status fetched successfully.', 'data' => ['plan' => 'none', 'status' => 'none', 'end'=>'none'], ];
            return json_encode($response);
        }
        $sub = WaSubscription::where('wa_user_id', $user->id)
            ->first();
        $plan = $sub->plan;
        $end = $sub->end;
        if ($plan == 'none' || $user == null)
        {
            $response = ['status' => 'success', 'message' => 'Subscription status fetched successfully.', 'data' => ['plan' => $plan, 'status' => 'none', 'end'=>'none'], ];
            return json_encode($response);
        }
        $start = $sub->start;
        $now = time();
        $your_date = strtotime($start);
        $datediff = $now - $your_date;
        $days_count = round($datediff / (60 * 60 * 24));
        if ($plan == 'monthly' && $days_count > 30)
        {
            $status = 'expired';
        }
        else if ($plan == 'yearly' && $days_count > 365)
        {
            $status = 'expired';

        }
        else
        {
            $status = 'active';
        }
        $response = ['status' => 'success', 'message' => 'Subscription status fetched successfully.', 'data' => ['plan' => $plan, 'status' => $status, 'end'=>$end], ];
        return json_encode($response);

    }
}

