<?php

namespace App\Http\Middleware;

use Closure;

class VerifySecretKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!isset(apache_request_headers()['Authorization'])) {
            $response = ['status'=>'error', 'message'=>'Secret Key Not Set', 'data'=>null];
            echo json_encode($response);
            die();
        }
        $header_auth = apache_request_headers()['Authorization'];
        if ($header_auth == 'Bearer 3Du9FOEZy3JWkwTmromCggcWcSKx9OuIqeHk71j0TCYYvAv31wOYrAlXYkqC3FLq') {
            return $next($request);
        }else {
            $response = ['status'=>'error', 'message'=>'Invalid Secret Key', 'data'=>null];
            echo json_encode($response);
            die();
        }
    }
}
