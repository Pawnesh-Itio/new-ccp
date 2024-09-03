<?php

namespace App\Http\Middleware;

use Closure;

class ApiCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($post, Closure $next)
    {
        if(!\Request::is('api/Directapi') &&!\Request::is('api/getip') && !\Request::is('api/getbal/*') && !\Request::is('api/callback/*') && !\Request::is('api/upi/*') && !\Request::is('api/ipayout/*') && !\Request::is('api/checkaeps/*') && !\Request::is('api/android/*')){
            if(!$post->has('token')){
                return response()->json(['statuscode'=>'ERR','status'=>'ERR','message'=> 'Invalid api token']);
            }
            
            $user = \App\Models\Apitoken::where('token', $post->token)->first();
            if(!$user){
                return response()->json(['statuscode'=>'ERR','status'=>'ERR','message'=> 'Invalid Domain or Ip Address or Api Token']);
            }
        }
        
        return $next($post);
    }
}
