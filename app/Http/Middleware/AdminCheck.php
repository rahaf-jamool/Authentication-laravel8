<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;

class AdminCheck
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
        // \Log::info('inside middlware');
        return $next($request);
        if(!Auth::check()){
             return response()->json([
                 'msg' => 'You are not allowed to access this route'
             ],404);
        }
        // $user = Auth::user();
        // if($user->role->isAdmin == 0){
        //     return response()->json([
        //         'msg' => 'You are not allowed to access this route'
        //     ],402); 
        // }

        // return $this->checkForPermission($user,$request);

        
        return $next($request);

    }
    // public function checkForPermission($user,$request){
    //     $permission = json_decode($user->role->permission);
    //     $hasPermission = false;
    //         foreach($permission as $p){
    //         if($p->name==$request->path()){
    //             if($p->read){
    //                 $hasPermission = true;
    //             }
    //         }
    //     }
    // }
}
