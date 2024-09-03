<?php

namespace App\Http\Middleware;

use Closure;

class ApiLogActivity
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($post, Closure $next)
    {   
        if(\Request::is('api/*')){
            $twodaysago = date('Y-m-d',strtotime("-2 days"));
            \DB::table('android_logs')->whereDate('created_at', '<', $twodaysago)->delete();
    
            $field = [];
            $field['ipaddress'] = $post->ip();
            $field['json_request'] = json_encode($post->all());
            $field['url'] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            \DB::table('android_logs')->insert($field);
            
        }   
        return $next($post);
    }
}
