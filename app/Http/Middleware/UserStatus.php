<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->status=='onboarding' && !$request->is('dashboard')){
            return redirect(route('home'));
        }
        if($request->user()->status=='block'){
            \Auth::logout();
            $request->session()->invalidate();
            return redirect('/login')->with(['error'=>'Your account have been blocked.']);
        }
        return $next($request);
    }
}
