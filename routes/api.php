<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Few Endpoint Routes for the API
Route::middleware('verify.secret.key')->group(function() {
    Route::post('/register', 'SubmitController@index');
    Route::get('/register', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    Route::post('/getvcf', 'GetVcfController@index');
    Route::get('/getvcf', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    Route::post('/report', 'ReportController@index');
    Route::get('/report', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    Route::post('/remove', 'RemoveController@index');
    Route::get('/remove', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    Route::post('/getcount', 'CustomController@getcount');
    Route::get('/getcount', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    Route::post('/gettotalcount', 'CustomController@gettotalcount');
    Route::get('/gettotalcount', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    Route::post('/getpremiumstatus', 'PremiumController@getpremiumstatus');
    Route::get('/getpremiumstatus', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    Route::post('/activatepremium', 'PremiumController@activatepremium');
    Route::get('/activatepremium', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });

    Route::post('/activatepremium', 'PremiumController@activatepremium');
    Route::get('/activatepremium', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });

    Route::post('/getallvcf', 'GetVcfController@getall');
    Route::get('/getallvcf', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    
    Route::post('/get2kvcf', 'GetVcfController@get2kvcf');
    Route::get('/get2kvcf', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });

    Route::post('/fetchoutdated', 'GetVcfController@fetchoutdated');
    Route::get('/fetchoutdated', function() {
        $response = ['status'=>'error', 'message'=>'Get request is not supported for this route', 'data'=>null];
        return json_encode($response);
    });
    
    Route::get('/getads', 'CustomController@getads');
    
    Route::post('/getsinglevcf', 'GetVcfController@getsinglevcfmain');
    
    Route::get('/getsinglevcf/{date}', 'GetVcfController@getsinglevcf');



});
