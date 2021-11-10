<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->role == 'user'){
                return $next($request);
            }else{
                return response([
                    'message' => 'You do not have user access.'
                ], 401);
            }
        }else{
            return response([
                'message' => 'You are not logged in.'
            ], 401);
        }

        
    }
}
